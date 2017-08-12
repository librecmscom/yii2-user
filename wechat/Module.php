<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat;

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
        '<action:(login|logout)>' => 'security/<action>',
        '<action:(register|resend|mobile)>' => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot' => 'recovery/request',
        'notice' => 'notification/index',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'setting/<action:\w+>' => 'setting/<action>',
        'authentication' => 'authentication/index',
        'space/<id:\d+>/coins' => 'space/coin',
        'space/<id:\d+>/credits' => 'space/credit',
        'space/<id:\d+>/followers' => 'space/follower',
        'space/<id:\d+>/followed/<type:\w+>' => 'space/attention',
        'space/<id:\d+>/collected/<type:\w+>' => 'space/collected',
        //这个默认不启用
        //'<slug:[-a-zA-Z0-9_]+>' => 'profile/show',
    ];
}