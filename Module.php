<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\helpers\FileHelper;
use yuncms\user\models\Data;
use yuncms\user\models\Doing;
use yuncms\user\models\Coin;
use yuncms\user\models\Credit;
use yuncms\user\models\Wallet;
use yuncms\user\models\WalletLog;
use yuncms\user\models\Notification;

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
     * @var int mobile changing strategy.
     */
    public $mobileChangeStrategy = self::STRATEGY_DEFAULT;

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
    public $mailViewPath = '@yuncms/user/views/mail';

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
        'notice' => 'notification/index',
        'recover/<id:\d+>/<code:[A-Za-z0-9_-]+>' => 'recovery/reset',
        'setting/<action:\w+>' => 'setting/<action>',
        //这个默认不启用
        //'<username:[A-Za-z0-9]+>' => 'profile/view',
    ];

    public $avatarUrl = '@uploads/avatar';

    public $avatarPath = '@uploadroot/avatar';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * 注册语言包
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/users/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => __DIR__ . '/messages',
        ];
    }

    /**
     * 获取头像的存储路径
     * @param int $userId
     * @return string
     */
    public function getAvatarPath($userId)
    {
        $avatarPath = Yii::getAlias($this->avatarPath) . '/' . $this->getAvatarHome($userId);
        if (!is_dir($avatarPath)) {
            FileHelper::createDirectory($avatarPath);
        }
        return $avatarPath . substr($userId, -2);
    }

    /**
     * 获取头像访问Url
     * @param int $userId 用户ID
     * @return string
     */
    public function getAvatarUrl($userId)
    {
        return Yii::getAlias($this->avatarUrl) . '/' . $this->getAvatarHome($userId) . substr($userId, -2);
    }

    /**
     * 获取头像路径
     *
     * @param int $userId 用户ID
     * @return string
     */
    public function getAvatarHome($userId)
    {
        $id = sprintf("%09d", $userId);
        $dir1 = substr($id, 0, 3);
        $dir2 = substr($id, 3, 2);
        $dir3 = substr($id, 5, 2);
        return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
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
        }
        return $message->send();
    }

    /**
     * 变更指定用户钱包 + 钱或 - 钱
     * @param int $user_id
     * @param string $currency
     * @param double $money
     * @param string $action
     * @param string $msg
     * @return bool
     */
    public function wallet($user_id, $currency, $money, $action = '', $msg = ''){
        $wallet = Wallet::findByUserID($user_id, $currency);
        $value = $wallet->money + $money;
        if ($money < 0 && $value < 0) {
            return false;
        }
        $transaction = Wallet::getDb()->beginTransaction();
        try {
            //更新用户钱包
            $wallet->updateAttributes(['money' => $value]);
            //创建钱包操作日志
            WalletLog::create([
                'wallet_id' => $wallet->id,
                'currency' => $currency,
                'money' => $money,
                'action' => $action,
                'msg' => $msg,
                'type' => $money > 0 ? WalletLog::TYPE_INC : WalletLog::TYPE_DEC
            ]);
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    /**
     * 金币变动
     * @param int $user_id
     * @param string $action
     * @param int $coins 金币数量
     * @param int $sourceId 源ID
     * @param null $sourceSubject 源标题
     * @return bool
     * @throws \yii\db\Exception
     */
    public function coin($user_id, $action, $coins = 0, $sourceId = 0, $sourceSubject = null)
    {
        $userData = Data::findOne($user_id);
        if ($userData) {
            $transaction = Data::getDb()->beginTransaction();
            try {
                $value = $userData->coins + $coins;
                if ($coins < 0 && $value < 0) {
                    return false;
                }
                //更新用户钱包
                $userData->updateAttributes(['coins' => $value]);
                /*记录详情数据*/
                Coin::create([
                    'user_id' => $user_id,
                    'action' => $action,
                    'source_id' => $sourceId,
                    'source_subject' => $sourceSubject,
                    'coins' => $coins,
                ]);
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 修改用户经验值
     * @param int $user_id 用户id
     * @param string $action 执行动作：提问、回答、发起文章
     * @param int $sourceId 源：问题id、回答id、文章id等
     * @param string $sourceSubject 源主题：问题标题、文章标题等
     * @param int $credits 经验值
     * @return bool  操作成功返回true 否则  false
     */
    public function credit($user_id, $action, $credits = 0, $sourceId = 0, $sourceSubject = null)
    {
        $userData = Data::findOne($user_id);
        if ($userData) {
            $transaction = Data::getDb()->beginTransaction();
            try {
                /*修改用户账户信息*/
                $userData->updateCounters(['credits' => $credits]);
                Credit::create([
                    'user_id' => $user_id,
                    'action' => $action,
                    'source_id' => $sourceId,
                    'source_subject' => $sourceSubject,
                    'credits' => $credits,
                ]);
                $transaction->commit();
                return true;
            } catch (\Exception $e) {
                $transaction->rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 发送用户通知
     * @param int $fromUserId
     * @param int $toUserId
     * @param string $type
     * @param string $subject
     * @param int $sourceId
     * @param string $content
     * @param string $referType
     * @param int $refer_id
     * @return bool
     */
    public function notify($fromUserId, $toUserId, $type, $subject = '', $sourceId = 0, $content = '', $referType = '', $refer_id = 0)
    {
        /*不能自己给自己发通知*/
        if ($fromUserId == $toUserId) {
            return false;
        }
        try {
            $notify = Notification::create([
                'user_id' => $fromUserId,
                'to_user_id' => $toUserId,
                'type' => $type,
                'subject' => $subject,
                'source_id' => $sourceId,
                'content' => strip_tags($content),
                'refer_type' => $referType,
                'refer_id' => $refer_id,
                'status' => Notification::STATUS_UNREAD
            ]);
            return $notify != false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 记录用户动态
     * @param int $userId 动态发起人
     * @param string $action 动作 ['ask','answer',...]
     * @param string $sourceType 被引用的内容类型
     * @param int $sourceId 问题或文章ID
     * @param string $subject 问题或文章标题
     * @param string $content 回答或评论内容
     * @param int $referId 问题或者文章ID
     * @param int $referUserId 引用内容作者ID
     * @param null $referContent 引用内容
     * @return bool
     */
    public function doing($userId, $action, $sourceType, $sourceId, $subject, $content = '', $referId = 0, $referUserId = 0, $referContent = null)
    {
        try {
            $doing = Doing::create([
                'user_id' => $userId,
                'action' => $action,
                'source_id' => $sourceId,
                'source_type' => $sourceType,
                'subject' => $subject,
                'content' => strip_tags($content),
                'refer_id' => $referId,
                'refer_user_id' => $referUserId,
                'refer_content' => strip_tags($referContent),
            ]);
            return $doing != false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 翻译语言
     * @param string $category
     * @param string $message
     * @param array $params
     * @param null $language
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/user/' . $category, $message, $params, $language);
    }
}
