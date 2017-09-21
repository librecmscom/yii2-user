<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;

/**
 * Class Settings
 * @package yuncms\user\backend\models
 */
class Settings extends Model
{
    /**
     * @var boolean 是否开启注册
     */
    public $enableRegistration;

    /**
     * @var boolean 开启手机注册
     */
    public $enableMobileRegistration;

    /**
     * @var boolean 开启注册验证码
     */
    public $enableRegistrationCaptcha;

    /**
     * @var boolean 开启自动生成密码
     */
    public $enableGeneratingPassword;

    /**
     * @var boolean 开启邮件激活
     */
    public $enableConfirmation;

    /**
     * @var boolean 开启未邮件激活用户登录
     */
    public $enableUnconfirmedLogin;

    /**
     * @var boolean 开启密码找回
     */
    public $enablePasswordRecovery;

    /**
     * @var integer 密码变动策略
     */
    public $emailChangeStrategy;

    /**
     * @var integer 手机变动策略
     */
    public $mobileChangeStrategy;

    /**
     * @var integer 记住我的时长
     */
    public $rememberFor;

    /**
     * @var integer 邮件激活链接的有效期
     */
    public $confirmWithin;

    /**
     * @var integer 找回密码的链接有效期
     */
    public $recoverWithin;

    /**
     * @var integer 密码盐的时间复杂度
     */
    public $cost;

    /**
     * Email is changed right after user enter's new email address.
     */
    const STRATEGY_INSECURE = 0;

    /**
     * Email is changed after user clicks confirmation link sent to his new email address.
     */
    const STRATEGY_DEFAULT = 1;

    /**
     * Email is changed after user clicks both confirmation links sent to his old and new email addresses.
     */
    const STRATEGY_SECURE = 2;

    public $avatarPath;
    public $avatarUrl;

    /**
     * 定义字段类型
     * @return array
     */
    public function getTypes()
    {
        return [
            'enableRegistration' => 'boolean',
            'enableMobileRegistration' => 'boolean',
            'enableRegistrationCaptcha' => 'boolean',
            'enableGeneratingPassword' => 'boolean',
            'enableConfirmation' => 'boolean',
            'enableUnconfirmedLogin' => 'boolean',
            'enablePasswordRecovery' => 'boolean',

            'emailChangeStrategy' => 'integer',
            'mobileChangeStrategy' => 'integer',
            'rememberFor' => 'integer',
            'confirmWithin' => 'integer',
            'recoverWithin' => 'integer',
            'cost' => 'integer',
            'avatarPath' => 'string',
            'avatarUrl' => 'string',
        ];
    }

    public function rules()
    {
        return [
            [[
                'enableRegistration',
                'enableMobileRegistration',
                'enableRegistrationCaptcha',
                'enableGeneratingPassword',
                'enableConfirmation',
                'enableUnconfirmedLogin',
                'enablePasswordRecovery'
            ], 'boolean'],

            [['enableRegistration', 'enableMobileRegistration', 'enableUnconfirmedLogin', 'enablePasswordRecovery'], 'default', 'value' => true],

            [['enableRegistrationCaptcha', 'enableGeneratingPassword', 'enableConfirmation'], 'default', 'value' => false],

            [[
                'emailChangeStrategy',
                'mobileChangeStrategy',

                'rememberFor',
                'confirmWithin',
                'recoverWithin',
                'cost',
            ], 'integer'],

            ['emailChangeStrategy', 'default', 'value' => self::STRATEGY_DEFAULT],
            ['mobileChangeStrategy', 'default', 'value' => self::STRATEGY_DEFAULT],

            ['emailChangeStrategy', 'in', 'range' => [self::STRATEGY_INSECURE, self::STRATEGY_DEFAULT, self::STRATEGY_SECURE]],
            ['mobileChangeStrategy', 'in', 'range' => [self::STRATEGY_INSECURE, self::STRATEGY_DEFAULT, self::STRATEGY_SECURE]],

            ['rememberFor', 'default', 'value' => 1209600],
            ['confirmWithin', 'default', 'value' => 86400],
            ['recoverWithin', 'default', 'value' => 21600],
            ['cost', 'default', 'value' => 10],

            [['avatarPath', 'avatarUrl'], 'string'],

            ['avatarPath', 'default', 'value' => '@root/uploads/avatar'],
            ['avatarUrl', 'default', 'value' => '@web/uploads/avatar'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'enableRegistration' => Yii::t('user', 'Enable Registration'),
            'enableMobileRegistration' => Yii::t('user', 'Enable Mobile Registration'),
            'enableRegistrationCaptcha' => Yii::t('user', 'Enable Registration Captcha'),
            'enableGeneratingPassword' => Yii::t('user', 'enable Generating Password'),
            'enableConfirmation' => Yii::t('user', 'enable Confirmation'),
            'enableUnconfirmedLogin' => Yii::t('user', 'enable Unconfirmed Login'),
            'enablePasswordRecovery' => Yii::t('user', 'Enable Password Recovery'),
            'emailChangeStrategy' => Yii::t('user', 'Email Change Strategy'),
            'mobileChangeStrategy' => Yii::t('user', 'Mobile Change Strategy'),
            'rememberFor' => Yii::t('user', 'Remember For'),
            'confirmWithin' => Yii::t('user', 'Confirm Within'),
            'recoverWithin' => Yii::t('user', 'Recover Within'),
            'cost' => Yii::t('user', 'Cost'),

            'avatarPath' => Yii::t('user', 'Avatar Path'),
            'avatarUrl' => Yii::t('user', 'Avatar Url'),
        ];
    }

    /**
     * 返回标识
     */
    public function formName()
    {
        return 'user';
    }
}