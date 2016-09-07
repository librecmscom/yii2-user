<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\backend\models;

use yii\base\Model;

class UserSettingForm extends Model
{
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


    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['enableRegistration', 'enableRegistrationCaptcha', 'enableGeneratingPassword', 'enableConfirmation', 'enableUnconfirmedLogin', 'enablePasswordRecovery'], 'boolean'],
        ];
    }
}