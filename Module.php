<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;

/**
 * This is the main module class for the yii2-user.
 */
class Module extends \yii\base\Module
{
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

    /**
     * @var bool Whether to show flash messages.
     */
    public $enableFlashMessages = false;

    /**
     * @var bool Whether to enable registration.
     */
    public $enableRegistration = true;

    /**
     * @var bool Whether to enable registration captcha.
     */
    public $enableRegistrationCaptcha = false;

    /**
     * @var bool Whether to remove password field from registration form.
     */
    public $enableGeneratingPassword = false;

    /**
     * @var bool Whether user has to confirm his account.
     */
    public $enableConfirmation = false;

    /**
     * @var bool Whether to allow logging in without confirmation.
     */
    public $enableUnconfirmedLogin = false;

    /**
     *
     * @var bool Whether to enable password recovery.
     */
    public $enablePasswordRecovery = true;

    /**
     * @var int Email changing strategy.
     */
    public $emailChangeStrategy = self::STRATEGY_DEFAULT;

    /**
     * @var int The time you want the user will be remembered without asking for credentials.
     */
    public $rememberFor = 1209600; // two weeks

    /**
     * @var int The time before a confirmation token becomes invalid.
     */
    public $confirmWithin = 86400; // 24 hours

    /**
     * @var int The time before a recovery token becomes invalid.
     */
    public $recoverWithin = 21600; // 6 hours

    /**
     * @var int Cost parameter used by the Blowfish hash algorithm.
     */
    public $cost = 10;

    /**
     * @var array Mailer configuration
     */
    public $mailViewPath = '@vendor/yuncms/yii2-user/views/mail';

    /**
     * @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com`
     */
    public $mailSender;

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
        '<id:\d+>' => 'profile/show',
        '<action:(login|logout)>' => 'security/<action>',
        '<action:(register|resend)>' => 'registration/<action>',
        'confirm/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'registration/confirm',
        'forgot' => 'recovery/request',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'settings/<action:\w+>' => 'settings/<action>'
    ];

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {
            /* @var $action \yii\base\Action */
            $view = $action->controller->getView();

            $view->params['breadcrumbs'][] = [
                'label' => Yii::t('user', 'User'),
                'url' => ['/' . $this->uniqueId]
            ];
            return true;
        }
        return false;
    }

    /**
     * 给用户发送邮件
     * @param string $to 收件箱
     * @param string $subject 标题
     * @param string $view 视图
     * @param array $params 参数
     * @return boolean
     */
    public function sendMessage($to, $subject, $view, $params = [])
    {
        /** @var \yii\mail\BaseMailer $mailer */
        $mailer = Yii::$app->mailer;
        $mailer->viewPath = $this->mailViewPath;
        $mailer->getView()->theme = Yii::$app->view->theme;
        $message = $mailer->compose(['html' => $view, 'text' => 'text/' . $view], $params)->setTo($to)->setSubject($subject);
        if ($this->mailSender != null) {
            $message->setFrom($this->mailSender);
        } else if (isset(Yii::$app->params['adminEmail'])) {
            $message->setFrom(Yii::$app->params['adminEmail']);
        }
        return $message->send();
    }

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('user/' . $category, $message, $params, $language);
    }
}
