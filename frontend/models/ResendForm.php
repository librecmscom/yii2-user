<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\models;

use Yii;
use yii\base\Model;
use yuncms\user\models\User;
use yuncms\user\ModuleTrait;
use yuncms\user\models\Token;
/**
 * ResendForm gets user email address and validates if user has already confirmed his account. If so, it shows error
 * message, otherwise it generates and sends new confirmation token to user.
 *
 * @property User $user
 */
class ResendForm extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $email;

    /**
     * @var User
     */
    private $_user;

    /**
     * @return User
     */
    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'emailRequired' => ['email', 'required'],
            'emailPattern' => ['email', 'email'],
            'emailExist' => ['email', 'exist', 'targetClass' => User::className()],
            'emailConfirmed' => ['email', function () {
                if ($this->user != null && $this->user->getIsConfirmed()) {
                    $this->addError('email', Yii::t('user', 'This account has already been confirmed'));
                }
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => Yii::t('user', 'Email')
        ];
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'resend-form';
    }

    /**
     * Creates new confirmation token and sends it to the user.
     *
     * @return boolean
     */
    public function resend()
    {
        if (!$this->validate()) {
            return false;
        }
        /** @var Token $token */
        $token = new Token(['user_id' => $this->user->id, 'type' => Token::TYPE_CONFIRMATION]);
        $token->save(false);
        $this->module->sendMessage($this->user->email,Yii::t('user', 'Confirm account on {0}', Yii::$app->name),'confirmation',['user' => $this->user, 'token' => $token]);
        Yii::$app->session->setFlash('info', Yii::t('user', 'A message has been sent to your email address. It contains a confirmation link that you must click to complete registration.'));
        return true;
    }
}
