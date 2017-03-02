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

    /** @inheritdoc */
    public function attributeLabels()
    {
        return [
            'type' => Yii::t('user', 'Credit Type'),
            'msg' => Yii::t('user', 'Credit Msg'),
            'value' => Yii::t('user', 'Credit Value'),
            'created_at' => Yii::t('user', 'Created At'),
        ];
    }

    /**
     * 获取类型字符
     * @return mixed|null
     */
    public function getActionText()
    {
        switch ($this->action) {
            case 'ask':
                return Yii::t('user', 'Ask questions');
                break;
            case 'answer_question':
                return Yii::t('user', 'Answered the question');
                break;
            case 'answer_adopted':
                return Yii::t('user', 'answer is adopted');
                break;
            default:
                return null;
                break;
        }
    }

    public static function create($attribute)
    {
        $model = new static ($attribute);
        if ($model->save()) {
            return $model;
        }
        return false;
    }
}