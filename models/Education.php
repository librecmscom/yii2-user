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
use yuncms\user\ModuleTrait;

/**
 * This is the model class for table "education".
 *
 * @property integer $user_id
 * @property string $school
 * @property string $department
 * @property string $date
 * @property string $degree
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Education extends ActiveRecord
{
    use ModuleTrait;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_education}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['school', 'filter', 'filter' => 'trim'],
            ['school', 'required'],

            ['department', 'filter', 'filter' => 'trim'],
            ['department', 'required'],

            ['degree', 'filter', 'filter' => 'trim'],
            ['degree', 'required'],

            ['date', 'filter', 'filter' => 'trim'],
            ['date', 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school' => Yii::t('user', 'Education School'),
            'department' => Yii::t('user', 'Education Department'),
            'degree' => Yii::t('user', 'Education Degree'),
            'date' => Yii::t('user', 'Enrollment Year'),
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->link('user', Yii::$app->user->identity);
        }
    }
}