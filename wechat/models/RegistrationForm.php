<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yuncms\user\ModuleTrait;
use yuncms\user\models\User;
/**
 * Registration form collects user input on registration process, validates it and creates new User model.
 */
class RegistrationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string Username
     */
    public $username;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username rules
            'usernameLength' => ['username', 'string', 'min' => 3, 'max' => 255],
            'usernameTrim' => ['username', 'filter', 'filter' => 'trim'],
            'usernamePattern' => ['username', 'match', 'pattern' => User::$usernameRegexp],
            'usernameRequired' => ['username', 'required'],
            'usernameUnique' => ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('user', 'This username has already been taken')],

            'registrationPolicyRequired' => ['registrationPolicy', 'required', 'skipOnEmpty' => false, 'requiredValue' => true,
                'message' => Yii::t('user', 'By registering you confirm that you accept the Service Agreement and Privacy Policy.'),],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('user', 'Username'),
            'password' => Yii::t('user', 'Password'),
            'verifyCode' => Yii::t('user', 'Verification Code'),
            'registrationPolicy' => Yii::t('user', 'Agree and accept Service Agreement and Privacy Policy'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'register-form';
    }

    /**
     * Registers a new user account. If registration was successful it will set flash message.
     *
     * @return boolean
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var User $user */
        $user = new User();
        //设置场景是微信注册
        $user->setScenario('wechat_register');
        $this->loadAttributes($user);
        if (!$user->register()) {
            return false;
        }
        Yii::$app->session->setFlash('info', Yii::t('user', 'Your account has been created and a message with further instructions has been sent to your email'));
        return Yii::$app->getUser()->login($user, $this->module->rememberFor);
    }

    /**
     * Loads attributes to the user model. You should override this method if you are going to add new fields to the
     * registration form. You can read more in special guide.
     *
     * By default this method set all attributes of this model to the attributes of User model, so you should properly
     * configure safe attributes of your User model.
     *
     * @param User $user
     */
    protected function loadAttributes(User $user)
    {
        $user->setAttributes($this->attributes);
    }
}
