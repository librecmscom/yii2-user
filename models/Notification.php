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
 * Notice model 通知模型
 *
 * @property integer $id
 * @property integer $user_id 发送方ID，系统消费为空
 * @property integer $to_user_id 接收方ID
 * @property string $type 通知类型代码
 * @property int $source_id 资源ID
 * @property string $subject 资源标题
 * @property string $refer_content 资源内容
 * @property integer $status 状态
 * @property integer $created_at 创建时间
 * @property integer $updated_at 更新时间
 */
class Notification extends ActiveRecord
{
    //未读
    const STATUS_UNREAD = 10;
    //已读
    const STATUS_READ = 20;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_notifcation}}';
    }

    /**
     * 定义行为
     * @return array
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at']
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
            ['status', 'default', 'value' => self::STATUS_UNREAD],
            ['status', 'in', 'range' => [self::STATUS_READ, self::STATUS_UNREAD]],
        ];
    }

    /**
     * @return \yii\db\ActiveQueryInterface 发送者用户实例
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery 接受者用户实例
     */
    public function getToUser()
    {
        return $this->hasOne(User::className(), ['id' => 'to_user_id']);
    }

    /**
     * 设置指定用户为全部已读
     * @param int $toUserId
     * @return int
     */
    public static function setReadAll($toUserId)
    {
        return self::updateAll(['status' => self::STATUS_READ], ['to_user_id' => $toUserId]);
    }

    public static function create($attribute)
    {
        $model = new static ($attribute);
        return $model->save();
    }
}