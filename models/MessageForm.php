<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\base\Model;

/**
 * Class MessageForm
 * @package yuncms\user\models
 */
class MessageForm extends Model
{

    public $parent;
    public $message;
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['message', 'filter', 'filter' => 'trim'],
            ['message', 'required'],

            ['parent', 'filter', 'filter' => 'trim'],
            ['parent', 'required'],

            ['username', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message' => Yii::t('user', 'Message'),
        ];
    }

    public function save()
    {
        if ($this->validate()) {
            $new = new Message();
            $new->from_id = Yii::$app->user->getId();
            $new->message = $this->message;
            if (!empty($this->parent)) {
                $message = Message::findOne($this->parent);
                if ($message->from_id == Yii::$app->user->getId()) {
                    $new->user_id = $message->user_id;
                } else {
                    $new->user_id = $message->from_id;
                }
                $new->link('parent', $message);
            } else {
                $user = User::findByUsername($this->username);
                $new->user_id = $user->id;
            }
            return $new->save();
        }
        return false;
    }
}