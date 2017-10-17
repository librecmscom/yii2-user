<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend;

class Module extends \yuncms\user\Module
{

    /**
     * @var string the default route of this module. Defaults to 'default'.
     * The route may consist of child module ID, controller ID, and/or action ID.
     * For example, `help`, `post/create`, `admin/post/create`.
     * If action ID is not given, it will take the default value as specified in
     * [[Controller::defaultAction]].
     */
    public $defaultRoute = 'profile';

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = 'user';

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        '<id:\d+>' => 'profile/view',
        [
            'class' => 'yii\web\UrlRule',
            'suffix' => '.html',
            'pattern' => '<action:(login|logout)>',
            'route' => 'security/<action>',
        ],
        [
            'class' => 'yii\web\UrlRule',
            'suffix' => '.html',
            'pattern' => '<action:(register|resend)>',
            'route' => 'registration/<action>',
        ],
        [
            'class' => 'yii\web\UrlRule',
            'suffix' => '.html',
            'pattern' => 'forgot',
            'route' => 'recovery/request',
        ],
        //'<action:(login|logout)>' => 'security/<action>',
        //'<action:(register|resend)>' => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        //'forgot' => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>' => 'settings/<action>',
        //这个默认不启用
    ];
}