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
 * Class MessageSendForm
 * @package yuncms\user\models
 */
class MessageSendForm extends Model
{

    public $parent;
    public $message;
    public $username;

    private $_user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message', 'username'], 'required'],
            [['username', 'message'], 'filter', 'filter' => 'trim'],
            ['username', 'validateUsername'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'message' => Yii::t('user', 'Message'),
        ];
    }

    /**
     * 验证用户是否已经报名
     * @param string $attribute
     * @param array $params
     */
    public function validateUsername($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user) {
                $this->addError($attribute, '你输入的用户名不存在哦！');
            }
        }
    }

    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->getUser();
            return true;
        }
        return false;
    }

    public function save()
    {
        if ($this->validate()) {
            $new = new Message();
            $new->from_id = Yii::$app->user->getId();
            $new->user_id = $this->_user->id;
            $new->message = $this->message;
            return $new->save();
        }
        return false;
    }

    /**
     * 通过手机号查询已经报名的用户
     *
     * @return User
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }
        return $this->_user;
    }
}