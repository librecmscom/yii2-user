<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Credit model
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property double $amount
 * @property integer $created_at
 */
class Credit extends ActiveRecord
{

    /**
     * @var integer 操作类型 加钱
     */
    const TYPE_INC = 1;

    /**
     * @var integer 操作类型 减钱
     */
    const TYPE_DEC = 0;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_credits}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [['type', 'in', 'range' => [self::TYPE_INC, self::TYPE_DEC]]];
    }

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('user', 'Purse Log Type'),
            'msg' => Yii::t('user', 'Purse Log Msg'),
            'value' => Yii::t('user', 'Purse Log Value'),
            'created_at' => Yii::t('user', 'Purse Log Created At'),
        ];
    }
}