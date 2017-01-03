<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * 钱包模型
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property double $amount
 * @property integer $created_at
 * @property integer $updated_at
 */
class Purse extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%purse}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['amount', 'default', 'value' => 0.00],
        ];
    }

    /**
     * 一对一关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * 快速创建实例
     * @param array $attribute
     * @return mixed
     */
    public static function create(array $attribute)
    {
        $model = new static ($attribute);
        if ($model->save()) {
            return $model;
        }
        return false;
    }

    /**
     * 通过用户ID查询用户钱包
     *
     * @param int $userID 用户ID
     * @param string $currency 币种
     * @return static
     */
    public static function findByUserID($userID, $currency)
    {
        $purse = static::findOne(['user_id' => $userID, 'currency' => $currency]);
        if (!$purse) {
            $purse = static::create(['user_id' => $userID, 'currency' => $currency, 'amount' => 0.00]);
        }
        return $purse;
    }

    /**
     * 定义乐观锁
     * @return string
     */
    public function optimisticLock()
    {
        return 'ver';
    }

    /**
     * 变更指定用户钱包 + 钱或 - 钱
     *
     * @param integer $userId 用户ID
     * @param string $currency 钱包类型
     * @param float $amount 变更的钱数 正数加钱，负数减钱
     * @param string $action 操作代码
     * @param string $msg 备注
     * @return bool true 操作成功，false 余额不足
     */
    public static function change($userId, $currency, $amount, $action = '', $msg = '')
    {
        //获取用户钱包
        $purse = static::findByUserID($userId, $currency);
        $value = $purse->amount + $amount;
        if ($amount < 0 && $value < 0) {
            return false;
        }
        $transaction = static::getDb()->beginTransaction();
        try {
            //更新用户钱包
            $purse->updateAttributes(['amount' => $value]);
            //创建钱包操作日志
            PurseLog::create([
                'purse_id' => $purse->id,
                'currency' => $currency,
                'value' => $amount,
                'action' => $action,
                'msg' => $msg,
                'type' => $amount > 0 ? PurseLog::TYPE_INC : PurseLog::TYPE_DEC
            ]);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }
}