<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\backend\models;

use Yii;
use yii\base\Model;

/**
 * Class Settings
 * @package yuncms\user\backend\models
 */
class Settings extends Model
{
    public $enableRegistration;
    public $enableRegistrationCaptcha;
    public $enableGeneratingPassword;
    public $enableConfirmation;
    public $enableUnconfirmedLogin;
    public $enablePasswordRecovery;

    public $rememberFor;
    public $confirmWithin;
    public $recoverWithin;
    public $cost;

    /**
     * 定义字段类型
     * @return array
     */
    public function getTypes()
    {
        return [
            'enableRegistration' => 'boolean',
            'enableRegistrationCaptcha' => 'boolean',
            'enableGeneratingPassword' => 'boolean',
            'enableConfirmation' => 'boolean',
            'enableUnconfirmedLogin' => 'boolean',
            'enablePasswordRecovery' => 'boolean',
            'rememberFor' => 'integer',
            'confirmWithin' => 'integer',
            'recoverWithin' => 'integer',
            'cost' => 'integer',
        ];
    }

    public function rules()
    {
        return [
            [['enableRegistration', 'enableRegistrationCaptcha',
                'enableGeneratingPassword', 'enableConfirmation',
                'enableUnconfirmedLogin', 'enablePasswordRecovery'
            ], 'boolean'],

            [['enableRegistration', 'enableUnconfirmedLogin', 'enablePasswordRecovery'], 'default', 'value' => true],

            [['enableRegistrationCaptcha', 'enableGeneratingPassword', 'enableConfirmation'], 'default', 'value' => false],

            [[
                'rememberFor',
                'confirmWithin',
                'recoverWithin',
                'cost',
            ], 'integer'],
            ['rememberFor', 'default', 'value' => 1209600],
            ['confirmWithin', 'default', 'value' => 86400],
            ['recoverWithin', 'default', 'value' => 21600],
            ['cost', 'default', 'value' => 10],
            //[['siteName', 'siteDescription'], 'string'],
        ];
    }

    public function fields()
    {
        return [
            'enableRegistration',
            'enableRegistrationCaptcha',
            'enableGeneratingPassword',
            'enableConfirmation',
            'enableUnconfirmedLogin',
            'enablePasswordRecovery',

            'rememberFor',
            'confirmWithin',
            'recoverWithin',
            'cost',
        ];
    }

    public function attributes()
    {
        return [
            'enableRegistration',
            'enableRegistrationCaptcha',
            'enableGeneratingPassword',
            'enableConfirmation',
            'enableUnconfirmedLogin',
            'enablePasswordRecovery',

            'rememberFor',
            'confirmWithin',
            'recoverWithin',
            'cost',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('system', 'Area ID'),
            'name' => Yii::t('system', 'Area Name'),
            'area_code' => Yii::t('system', 'Area Code'),
            'post_code' => Yii::t('system', 'Post Code'),
            'parent' => Yii::t('system', 'Parent Area'),
            'parent_name' => Yii::t('system', 'Parent Area Name'),
            'sort' => Yii::t('system', 'Area Sort'),
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