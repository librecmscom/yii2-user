<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\backend\models;

use Yii;
use yii\base\Model;

class UserSettingForm extends Model
{
    public $mailSender;

    public $mailViewPath;
    /**
     * @var bool 是否允许用户注册
     */
    public $enableRegistration;

    /**
     * @var bool 是否开启注册验证码
     */
    public $enableRegistrationCaptcha;

    /**
     * @var bool 是否自动生成密码
     */
    public $enableGeneratingPassword;

    /**
     * @var bool 是否启用邮件激活
     */
    public $enableConfirmation;

    /**
     * @var
     */
    public $enableUnconfirmedLogin;

    /**
     * @var bool 是否启用密码找回
     */
    public $enablePasswordRecovery;

    public $enableAccountDelete;

    public $emailChangeStrategy;

    /**
     * @var int Cost parameter used by the Blowfish hash algorithm.
     */
    public $cost;

    /**
     * @var int 记住我时长
     */
    public $rememberFor;

    /** @var int The time before a confirmation token becomes invalid. */
    public $confirmWithin;

    /** @var int The time before a recovery token becomes invalid. */
    public $recoverWithin;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['mailSender','email'],
            ['mailViewPath','string'],
            [[
                'enableRegistration',
                'enableRegistrationCaptcha',
                'enableGeneratingPassword',
                'enableConfirmation',
                'enableUnconfirmedLogin',
                'enablePasswordRecovery',
                'enableAccountDelete'
            ], 'boolean'],
            ['emailChangeStrategy', 'integer', 'max' => 2, 'min' => 0],
            ['cost', 'integer', 'max' => 10, 'min' => 6],
            [['rememberFor', 'confirmWithin', 'recoverWithin'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableRegistration' => Yii::t('user', 'Enable Registration'),
            'enableRegistrationCaptcha' => Yii::t('user', 'Enable Registration Captcha'),
            'enableGeneratingPassword' => Yii::t('user', 'Enable Generating Password'),
            'enableConfirmation' => Yii::t('user', 'Enable Confirmation'),
            'enableUnconfirmedLogin' => Yii::t('user', 'Enable Unconfirmed Login'),
            'enablePasswordRecovery' => Yii::t('user', 'Enable Password Recovery'),
            'cost' => Yii::t('user', 'Enable Password Recovery'),
            'enablePasswordRecovery' => Yii::t('user', 'Enable Password Recovery'),
            'enablePasswordRecovery' => Yii::t('user', 'Enable Password Recovery'),
            'enablePasswordRecovery' => Yii::t('user', 'Enable Password Recovery'),
        ];
    }
}