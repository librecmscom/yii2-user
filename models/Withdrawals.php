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
 * @property string $currency
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
            [['bankcard_id', 'money'], 'required'],
            [['bankcard_id'], 'integer'],
            ['money', 'integer',
                'min' => 100,
                'tooSmall' => Yii::t('user', 'Please enter the correct money.')
            ],
            ['country', 'validateCountry'],
            ['status', 'default', 'value' => self::STATUS_PENDING],
            ['status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_REJECTED, self::STATUS_AUTHENTICATED]],
        ];
    }

    public function validateCountry($attribute, $params)
    {
        $wallet = Wallet::find()->where(['user_id' => Yii::$app->user->identity->id, 'currency' => $this->currency])->one();
        if ($this->money < $wallet->money) {
            $this->addError($attribute, Yii::t('user', 'Insufficient money, please recharge.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('user', 'User Id'),
            'status' => Yii::t('user', 'Status'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
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