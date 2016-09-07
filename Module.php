<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user;

use Yii;

/**
 * Class Module
 * @package yuncms\user
 */
class Module extends \yii\base\Module
{
    /** Email is changed right after user enter's new email address. */
    const STRATEGY_INSECURE = 0;

    /** Email is changed after user clicks confirmation link sent to his new email address. */
    const STRATEGY_DEFAULT = 1;

    /** Email is changed after user clicks both confirmation links sent to his old and new email addresses. */
    const STRATEGY_SECURE = 2;
    
    /**
     * @var string|array Default: `Leaps::$app->params['adminEmail']` OR `no-reply@example.com`
     */
    public $mailSender;

    /**
     * @var array Mailer configuration
     */
    public $mailViewPath = '@yuncms/user/views/mail';

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
}