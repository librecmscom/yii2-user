<?php
/**
 * Leaps Framework [ WE CAN DO IT JUST THINK IT ]
 *
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace Leaps\User\Widget;

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
