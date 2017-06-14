<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
use yuncms\user\helpers\Password;
use yuncms\user\ModuleTrait;

/**
 * LoginForm get user's login and password, validates them and logs the user in. If user has been blocked, it adds
 * an error to login form.
 */
class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string User's email or username
     */
    public $login;

    /**
     * @var string User's plain password
     */
    public $password;

    /**
     * @var bool Whether to remember the user
     */
    public $rememberMe = false;

    /**
     * @var \yuncms\user\models\User
     */
    protected $user;


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login' => Yii::t('user', 'Login'),
            'password' => Yii::t('user', 'Password'),
            'rememberMe' => Yii::t('user', 'Remember me next time'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'requiredFields' => [['login', 'password'], 'required'],
            'loginTrim' => ['login', 'trim'],
            'passwordValidate' => [
                'password',
                function ($attribute) {
                    if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                        $this->addError($attribute, Yii::t('user', 'Invalid login or password'));
                    }
                }
            ],
            'confirmationValidate' => [
                'login',
                function ($attribute) {
                    if ($this->user !== null) {
                        $confirmationRequired = $this->module->enableConfirmation && !$this->module->enableUnconfirmedLogin;
                        if ($confirmationRequired && !$this->user->getIsConfirmed()) {
                            $this->addError($attribute, Yii::t('user', 'You need to confirm your email address.'));
                        }
                        if ($this->user->getIsBlocked()) {
                            $this->addError($attribute, Yii::t('user', 'Your account has been blocked.'));
                        }
                    }
                }
            ],
            'rememberMe' => ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $loginHistory = new LoginHistory(['ip' => Yii::$app->request->userIP]);
            $loginHistory->link('user', $this->user);

            return Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'login-form';
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = User::findByUsernameOrEmail($this->login);
            return true;
        } else {
            return false;
        }
    }
}
