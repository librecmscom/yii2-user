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
 * This is the model class for table "career".
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
class Career extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_career}}';
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
            [['name', 'position', 'city', 'description', 'start_at', 'end_at',], 'required'],
            [['name', 'position', 'city', 'description', 'start_at', 'end_at'], 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Company'),
            'position' => Yii::t('user', 'Position'),
            'city' => Yii::t('user', 'City'),
            'start_at' => Yii::t('user', 'From'),
            'end_at' => Yii::t('user', 'To'),
            'description' => Yii::t('user', 'Career Description'),
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