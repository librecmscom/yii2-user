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
}