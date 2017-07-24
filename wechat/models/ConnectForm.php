<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\models;

use Yii;
use yii\base\Model;
use yuncms\user\helpers\Password;
use yuncms\user\models\User;
use yuncms\user\models\Wechat;
use yuncms\user\ModuleTrait;

/**
 * 注册或绑定用户
 */
class ConnectForm extends Model
{
    use ModuleTrait;

    /**
     * @var string User's email
     */
    public $email;

    /**
     * @var string User's plain password
     */
    public $password;

    /**
     * @var \yuncms\user\models\User
     */
    protected $user;

    /**
     * @var Wechat 社交账户实例
     */
    public $wechat;

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
            // email rules
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],

            'passwordRequired' => ['password', 'required'],
            'passwordLength' => ['password', 'string', 'min' => 6],
            'passwordTrim' => ['password', 'filter', 'filter' => 'trim'],
        ];
    }

    /**
     * Validates form and logs the user in.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function connect()
    {
        if ($this->validate()) {
            $this->user = User::findByEmail($this->email);
            if ($this->user === null) {
                $this->user = Yii::createObject([
                    'class' => User::className(),
                    'scenario' => 'wechat_register',
                    'email' => $this->email,
                    'password' => $this->password
                ]);
                $this->user->generateName();//生成用户名
                if (!$this->user->create()) {
                    $this->addErrors($this->user->getErrors());
                    return false;
                }
            } else {
                if (!Password::validate($this->password, $this->user->password_hash)) {
                    $this->addError('password', Yii::t('user', 'Invalid login or password'));
                    return false;
                }
                if ($this->user->getIsBlocked()) {
                    $this->addError('login', Yii::t('user', 'Your account has been blocked.'));
                    return false;
                }
            }
            $this->wechat->connect($this->user);
            return Yii::$app->user->login($this->user, $this->module->rememberFor);
        } else {
            return false;
        }
    }

    public function setWechat(Wechat $wechat)
    {
        $this->wechat = $wechat;
    }
}
