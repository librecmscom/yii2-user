<?php

namespace yuncms\user\frontend\widgets;

use Yii;
use yii\base\Widget;
use yuncms\user\frontend\models\LoginForm;

/**
 * Class Login
 * @package yuncms\user\frontend\widgets
 */
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
