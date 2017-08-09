<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use yuncms\user\models\User;
use yuncms\user\ModuleTrait;
use yuncms\system\validators\MobileValidator;
use xutl\sms\captcha\CaptchaValidator;

/**
 * MobileRegistration form collects user input on registration process, validates it and creates new User model.
 */
class MobileRegistrationForm extends Model
{
    use ModuleTrait;

    /**
     * @var string mobile
     */
    public $mobile;

    /**
     * @var string Password
     */
    public $password;

    /**
     * @var bool 是否同意注册协议
     */
    public $registrationPolicy;

    /**
     * @var string 验证码
     */
    public $verifyCode;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile', 'verifyCode'], 'required'],
            ['mobile', 'integer'],
            ['mobile', 'match', 'pattern' => '/^1[0-9]{10}$/', 'message' => Yii::t('user', 'Please enter the correct phone number.')],
            ['mobile', MobileValidator::className()],
            ['mobile', 'validateMobile'],

            ['verifyCode', 'integer'],
            ['verifyCode', 'string', 'min' => 4, 'max' => 6],
            ['verifyCode', CaptchaValidator::className(), 'captchaAction' => '/user/registration/sms-captcha', 'skipOnEmpty' => false, 'message' => Yii::t('user', 'Phone verification code input error.')],

            // password rules
            'passwordRequired' => ['password', 'required', 'skipOnEmpty' => $this->module->enableGeneratingPassword],
            'passwordLength' => ['password', 'string', 'min' => 6],

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
            'mobile' => Yii::t('user', 'Mobile'),
            'verifyCode' => Yii::t('user', 'Verification Code'),
            'password' => Yii::t('user', 'Password'),
            'registrationPolicy' => Yii::t('user', 'Agree and accept Service Agreement and Privacy Policy'),
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $v = new CaptchaValidator([
                'captchaAction' => '/user/registration/sms-captcha',
            ]);
            if (!$v->validateMobile($this->mobile)) {
                $this->addError($attribute, Yii::t('user', 'Please enter the correct phone number.'));
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'mobile-form';
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
        $user->setScenario('mobile_register');
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
