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
 * @property Message $lastMessage
 * @property User $user
 * @property Message[] $Messages
 * @package yuncms\user\models
 */
class Message extends ActiveRecord
{
    const STATUS_NEW = false;
    const STATUS_READ = true;

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
            ['status', 'in', 'range' => [static::STATUS_NEW, static::STATUS_READ], 'message' => Yii::t('user', 'Incorrect status')],
        ];
    }

    /**
     * 设置已读
     * @return int
     */
    public function setRead()
    {
        return $this->updateAttributes(['status' => static::STATUS_READ]);
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFrom()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'from_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * 收件人是不是我自己
     * @return bool
     */
    public function isRead()
    {
        return $this->status == static::STATUS_READ;
    }

    /**
     * 收件人是不是我自己
     * @return bool
     */
    public function isRecipient()
    {
        return Yii::$app->user->id == $this->user_id;
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

    /**
     * 获取会话最后一行
     * @return $this|array|null|ActiveRecord
     */
    public function getLastMessage()
    {
        if (($message = $this->getMessages()->orderBy(['created_at' => SORT_DESC])->limit(1)->one()) != null) {
            return $message;
        }
        return $this;
    }
}