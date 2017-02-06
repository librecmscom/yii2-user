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
 * Class Message
 * @property int $id
 * @property int $user_id
 * @property int $from_id
 * @property int $parent
 * @property string $message
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @package yuncms\user\models
 */
class Message extends ActiveRecord
{
    const STATUS_NEW = '1';
    const STATUS_READ = '2';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_message}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'message'], 'required'],
            [['from_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'string', 'max' => 750],
            [['status'], 'default', 'value' => static::STATUS_NEW],
            ['status', 'in', 'range' => [static::STATUS_NEW, static::STATUS_READ], 'message' => Yii::t('user','Incorrect status')],
        ];
    }

    /**
     * 返回父短消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(static::className(), ['id' => 'parent']);
    }

    public function getFrom()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'from_id']);
    }

    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * 返回子短消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMessages()
    {
        return $this->hasMany(static::className(), ['parent' => 'id']);
    }
}