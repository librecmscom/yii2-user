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
 * This is the model class for table "education".
 *
 * @property integer $id
 * @property integer $user_id 用户ID
 * @property string $school 学校
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
            [['school', 'department', 'degree', 'date'], 'required'],
            [['school', 'department', 'degree', 'date'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'school' => Yii::t('user', 'School'),
            'department' => Yii::t('user', 'Education Department'),
            'degree' => Yii::t('user', 'Degree'),
            'date' => Yii::t('user', 'Year of graduation'),
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