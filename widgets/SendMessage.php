<?php

namespace yuncms\user\widgets;

use yii\base\Widget;
use yii\base\InvalidConfigException;
use yuncms\user\models\MessageSendForm;

class SendMessage extends Widget
{
    /** @var bool */
    public $validate = true;

    /** @inheritdoc */
    public function run()
    {
        $model = new MessageSendForm();
        return $this->render('send_message', [
            'model' => $model
        ]);
    }
}