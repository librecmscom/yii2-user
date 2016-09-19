<?php

namespace yuncms\user\widgets;

use Yii;
use yii\base\Widget;
use yuncms\user\models\LoginForm;

class Login extends Widget
{
    /** @var bool */
    public $validate = true;

    /** @inheritdoc */
    public function run()
    {
        return $this->render('login', [
            'model' => new LoginForm(),
        ]);
    }
}
