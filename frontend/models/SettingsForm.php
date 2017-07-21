<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\models;

use Yii;
use yii\base\Model;
use yuncms\user\Module;
use yuncms\user\helpers\Password;
use yuncms\user\models\User;
use yuncms\user\ModuleTrait;

/**
 * SettingsForm gets user's username, email and password and changes them.
 *
 * @property User $user
 */
class SettingsForm extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $slug;
    /**
     * @var string
     */
    public $new_password;
    /**
     * @var string
     */
    public $current_password;

    /**
     * @var User
     */
    private $_user;


    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        $this->setAttributes(['name' => $this->user->name,'slug' => $this->user->slug, 'email' => $this->user->unconfirmed_email ?: $this->user->email], false);
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'slugRequired' => ['slug', 'required'],
            'slugTrim' => ['slug', 'filter', 'filter' => 'trim'],
            'slugLength' => ['slug', 'string', 'min' => 3, 'max' => 50],
            'slugPattern' => ['slug', 'match', 'pattern' => User::$slugRegexp],
            'nameRequired' => ['name', 'required'],
            'nameTrim' => ['name', 'filter', 'filter' => 'trim'],
            'nameLength' => ['name', 'string', 'min' => 3, 'max' => 255],
            'namePattern' => ['name', 'match', 'pattern' => User::$nameRegexp],
            'emailRequired' => ['email', 'required'],
            'emailTrim' => ['email', 'filter', 'filter' => 'trim'],
            'emailPattern' => ['email', 'email'],
            'emailUsernameUnique' => [['email', 'slug'], 'unique', 'when' => function ($model, $attribute) {
                return $this->user->$attribute != $model->$attribute;
            }, 'targetClass' => User::className()],
            'newPasswordLength' => ['new_password', 'string', 'min' => 6],
            'currentPasswordRequired' => ['current_password', 'required'],
            'currentPasswordValidate' => ['current_password', function ($attr) {
                if (!Password::validate($this->$attr, $this->user->password_hash)) {
                    $this->addError($attr, Yii::t('user', 'Current password is not valid'));
                }
            }]];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'slug' => Yii::t('user', 'Slug'),
            'email' => Yii::t('user', 'Email'),
            'name' => Yii::t('user', 'Name'),
            'new_password' => Yii::t('user', 'New password'),
            'current_password' => Yii::t('user', 'Current password')];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'settings-form';
    }

    /**
     * Saves new account settings.
     *
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            $this->user->scenario = 'settings';
            $this->user->slug = $this->slug;
            $this->user->name = $this->name;
            $this->user->password = $this->new_password;
            if ($this->email == $this->user->email && $this->user->unconfirmed_email != null) {
                $this->user->unconfirmed_email = null;
            } elseif ($this->email != $this->user->email) {
                switch ($this->module->emailChangeStrategy) {
                    case Module::STRATEGY_INSECURE:
                        $this->insecureEmailChange();
                        break;
                    case Module::STRATEGY_DEFAULT:
                        $this->defaultEmailChange();
                        break;
                    case Module::STRATEGY_SECURE:
                        $this->secureEmailChange();
                        break;
                    default:
                        throw new \OutOfBoundsException('Invalid email changing strategy');
                }
            }
            return $this->user->save();
        }
        return false;
    }

    /**
     * Changes user's email address to given without any confirmation.
     */
    protected function insecureEmailChange()
    {
        $this->user->email = $this->email;
        Yii::$app->session->setFlash('success', Yii::t('user', 'Your email address has been changed'));
    }

    /**
     * Sends a confirmation message to user's email address with link to confirm changing of email.
     */
    protected function defaultEmailChange()
    {
        $this->user->unconfirmed_email = $this->email;
        /** @var Token $token */
        $token = new Token(['user_id' => $this->user->id, 'type' => Token::TYPE_CONFIRM_NEW_EMAIL]);
        $token->save(false);
        $this->module->sendMessage($this->user->unconfirmed_email,Yii::t('user', 'Confirm email change on {0}', Yii::$app->name),'reconfirmation',['user' => $this->user, 'token' => $token]);
        Yii::$app->session->setFlash('info', Yii::t('user', 'A confirmation message has been sent to your new email address'));
    }

    /**
     * Sends a confirmation message to both old and new email addresses with link to confirm changing of email.
     *
     * @throws \yii\base\InvalidConfigException
     */
    protected function secureEmailChange()
    {
        $this->defaultEmailChange();
        /** @var Token $token */
        $token = new Token(['user_id' => $this->user->id, 'type' => Token::TYPE_CONFIRM_OLD_EMAIL]);
        $token->save(false);
        $this->module->sendMessage($this->user->email,Yii::t('user', 'Confirm email change on {0}', Yii::$app->name),'reconfirmation',['user' => $this->user, 'token' => $token]);
        // unset flags if they exist
        $this->user->flags &= ~User::NEW_EMAIL_CONFIRMED;
        $this->user->flags &= ~User::OLD_EMAIL_CONFIRMED;
        $this->user->save(false);
        Yii::$app->session->setFlash('info', Yii::t('user', 'We have sent confirmation links to both old and new email addresses. You must click both links to complete your request'));
    }
}
