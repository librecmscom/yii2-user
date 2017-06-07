<?php

namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yuncms\user\ModuleTrait;
use yuncms\payment\models\Payment;
use yuncms\payment\OrderInterface;

/**
 * This is the model class for table "{{%user_coin_recharge}}".
 *
 * @property int $id
 * @property string $payment_id 支付号
 * @property int $user_id 用户ID
 * @property int $name
 * @property string $gateway 支付网关
 * @property string $currency 支付币种
 * @property string $money 支付金额
 * @property int $trade_state
 * @property int $trade_type 交易类型
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Recharge extends ActiveRecord implements OrderInterface
{
    use ModuleTrait;
    //交易状态
    const STATE_NOT_PAY = 0;//未支付
    const STATE_SUCCESS = 1;//支付成功
    const STATE_FAILED = 2;//支付失败

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'yii\behaviors\TimestampBehavior',
            [
                'class' => 'yii\behaviors\BlameableBehavior',
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['user_id'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_coin_recharge}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'trade_type'], 'integer'],
            [['gateway', 'currency', 'trade_type'], 'required'],
            [['money'], 'number'],

            [['currency'], 'string', 'max' => 20],

            ['trade_state', 'default', 'value' => self::STATE_NOT_PAY],
            ['trade_state', 'in', 'range' => [
                self::STATE_NOT_PAY,
                self::STATE_SUCCESS,
                self::STATE_FAILED,
            ]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'payment_id' => Yii::t('user', 'Payment Id'),
            'user_id' => Yii::t('user', 'User ID'),
            'name' => Yii::t('user', 'Name'),
            'gateway' => Yii::t('user', 'Gateway'),
            'currency' => Yii::t('user', 'Currency'),
            'money' => Yii::t('user', 'Money'),
            'trade_state' => Yii::t('user', 'Trade State'),
            'trade_type' => Yii::t('user', 'Trade Type'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }

    /**
     * @inheritdoc
     * @return RechargeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RechargeQuery(get_called_class());
    }

    /**
     * User Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Payment Relation
     * @return \yii\db\ActiveQueryInterface
     */
    public function getPayment()
    {
        return $this->hasOne(Payment::className(), ['id' => 'payment_id']);
    }

    /**
     * 计算用户获得的积分数量
     * @param integer $money 钱数
     * @param string $currency 币种
     * @return float
     */
    private static function getCoin($money, $currency = 'CNY')
    {
        $coin = $money;
        if ($currency == 'CNY') {//10比1
            if ($money >= 200 && $money < 500) {
                $coin += $coin * 0.5;
            } else if ($money >= 500) {//冲多少送多少
                $coin += $coin;
            }
        } else if ($currency == 'USD') {//2比1
            if ($money >= 20) {
                $coin += $coin * 0.1;
            } else if ($money >= 50 && $money < 100) {
                $coin += $coin * 0.2;
            } else if ($money >= 100) {//冲多少送多少
                $coin += $coin;
            }
        }
        //test
        if ($money == 0.01) {
            $coin = 0.01;
        }
        return $coin;
    }

    /**
     * 设置支付状态
     * @param string $orderId 订单ID
     * @param string $paymentId 支付号
     * @param bool $status 支付状态
     * @param array $params 附加参数
     * @return bool
     */
    public static function setPayStatus($orderId, $paymentId, $status, $params)
    {
        if (($model = static::findOne(['id' => $orderId])) != null && $status == true) {
            $coin = static::getCoin($model->money, $model->currency);
            coin($model->user_id, 'purchase', $coin);
            return true;
        }
        return false;
    }
}
