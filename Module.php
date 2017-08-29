<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\FileHelper;
use yuncms\user\models\User;
use yuncms\user\models\Data;
use yuncms\user\models\Doing;
use yuncms\user\models\Coin;
use yuncms\user\models\Credit;
use yuncms\user\models\Notification;
use yuncms\system\helpers\DateHelper;

/**
 * This is the main module class for the yii2-user.
 */
class Module extends \yii\base\Module
{
    /**
     * @var array Mailer configuration
     */
    public $mailViewPath = '@yuncms/user/mail';

    /**
     * @var string|array Default: `Yii::$app->params['adminEmail']` OR `no-reply@example.com`
     */
    public $mailSender;

    public $avatarUrl = '@web/uploads/avatar';

    public $avatarPath = '@root/uploads/avatar';



    /**
     * 获取用户总数
     * @param null|int $duration 缓存时间
     * @return int
     */
    public function getTotal($duration = null)
    {
        $total = User::getDb()->cache(function ($db) {
            return User::find()->count();
        }, $duration);
        return $total;
    }

    /**
     * 获取今日注册用户总数
     * @param null|int $duration 缓存时间
     * @return int|string
     */
    public function getTodayTotal($duration = null)
    {
        $total = User::getDb()->cache(function ($db) {
            return User::find()->where(['between', 'created_at', DateHelper::todayFirstSecond(), DateHelper::todayLastSecond()])->count();
        }, $duration);
        return $total;
    }

    /**
     * 获取今日活跃用户
     * @param null $duration
     * @return mixed
     */
    public function getTodayActivityTotal($duration = null)
    {
        $total = User::getDb()->cache(function ($db) {
            return User::find()->joinWith(['extend' => function (ActiveQuery $query) {
                $query->where(['between', '{{%user_extend}}.login_at', DateHelper::todayFirstSecond(), DateHelper::todayLastSecond()]);
            }])->count();
        }, $duration);
        return $total;
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
        if (empty($to)) {
            return false;
        }
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




}
