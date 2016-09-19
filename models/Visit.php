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
 * This is the model class for table "user_visit".
 *
 * @property integer $user_id 我自己的ID
 * @property integer $visit_id 访客ID
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 * @property User $user
 */
class Visit extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_visit}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['visit_id', 'filter', 'filter' => 'trim'],
            ['visit_id', 'required'],
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
     * @return \yii\db\ActiveQueryInterface
     */
    public function getVisit()
    {
        return $this->hasOne(User::className(), ['id' => 'visit_id']);
    }

    /**
     * 记录我的访客
     *
     * @param integer $user_id 我的ID
     * @param integer $visit_id 访客ID
     */
    public static function Add($user_id, $visit_id)
    {
        if ($user_id != $visit_id) {
            $visit = static::findOne(['user_id' => $user_id, 'visit_id' => $visit_id]);
            if ($visit == null) {
                $visit = new static(['user_id' => $user_id, 'visit_id' => $visit_id]);
            }
            $visit->save();
        }

    }
}