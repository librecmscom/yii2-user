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
 * Doing model 模型
 *
 * @property integer $id
 * @property integer $user_id 发送方ID，系统消费为空
 * @property string $action 操作代码
 * @property integer $source_id 资源ID
 * @property string $source_type 资源类型
 * @property string $subject 资源标题
 * @property string $content 资源内容
 * @property integer $refer_id
 * @property integer $refer_user_id
 * @property string $refer_content
 * @property integer $created_at 创建时间
 */
class Doing extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_doing}}';
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
     * @return \yii\db\ActiveQueryInterface 发送者用户实例
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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