<?php

namespace yuncms\user\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yuncms\user\models\MessageSendForm;

class SendMessage extends Widget
{
    /** @var bool */
    public $validate = true;

    /**
     * @var string 收件人用户名
     */
    public $username;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        if (empty ($this->username)) {
            throw new InvalidConfigException ('The "username" property must be set.');
        }
    }


    /** @inheritdoc */
    public function run()
    {
        $model = new MessageSendForm();
        $model->username = $this->username;
        return $this->render('send_message', [
            'model' => $model
        ]);
    }
}