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
 * 提现模型
 * @property int $id
 * @property int $user_id
 * @property int $bankcard_id
 * @property string $currency
 * @property double $money
 * @package yuncms\user\models
 */
class Withdrawals extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_REJECTED = 1;
    const STATUS_AUTHENTICATED = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_withdrawals}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],
            'blameable' => [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'user_id',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bankcard_id', 'money', 'currency'], 'required'],
            [['bankcard_id'], 'integer'],
            ['money', 'validateMoney'],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_REJECTED, self::STATUS_AUTHENTICATED]],
        ];
    }

    /**
     * 验证钱数
     * @param $attribute
     * @param $params
     */
    public function validateMoney($attribute, $params)
    {
        if($this->money < 0){
            $this->addError($attribute, Yii::t('user', 'Please enter the correct money.'));
            return;
        }
        $wallet = Wallet::find()->where(['user_id' => Yii::$app->user->identity->id, 'currency' => $this->currency])->one();
        if(!$wallet){
            $this->addError($attribute, Yii::t('user', 'Insufficient money.'));
            return;
        }
        if ($this->money < $wallet->money) {
            $this->addError($attribute, Yii::t('user', 'Insufficient money, please recharge.'));
            return;
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User Id'),
            'bankcard_id' => Yii::t('user', 'BankCard'),
            'currency' => Yii::t('user', 'Currency'),
            'Money' => Yii::t('user', 'Money'),
            'status' => Yii::t('user', 'Status'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }

    /**
     * 用户关系定义
     * @return \yii\db\ActiveQueryInterface
     */
    public function getBankcard()
    {
        return $this->hasOne(Bankcard::className(), ['id' => 'bankcard_id']);
    }

    /**
     * 用户关系定义
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }
}