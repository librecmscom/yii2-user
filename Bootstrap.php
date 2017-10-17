<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\web\GroupUrlRule;
use yii\i18n\PhpMessageSource;
use yii\base\BootstrapInterface;
use yuncms\user\jobs\LastVisitJob;

/**
 * Class Bootstrap
 * @package yuncms/user
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * 初始化
     * @param \yii\base\Application $app
     * @throws \yii\base\InvalidConfigException
     */
    public function bootstrap($app)
    {
        if ($app instanceof \yii\console\Application) {
            $app->controllerMap['user'] = [
                'class' => 'yuncms\user\console\UserController',
            ];
        } else if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            if ($module instanceof \yuncms\user\frontend\Module) {//前台判断放最后
                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => ['/user/security/login'],
                    'identityClass' => 'yuncms\user\models\User',
                    'identityCookie' => ['name' => '_identity_frontend', 'httpOnly' => true],
                    'idParam' => '_user',
                ]);
                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules' => $module->urlRules,
                ];
                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }
                $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);

                //监听用户登录事件
                /** @var \yii\web\UserEvent $event */
                $app->user->on(\yii\web\User::EVENT_AFTER_LOGIN, function ($event) {
                    //记录最后登录时间记录最后登录IP记录登录次数
                    $event->identity->resetLoginData();
                });
                //设置用户所在时区
//                $app->on(\yii\web\Application::EVENT_BEFORE_REQUEST, function ($event) use ($app) {
//                    if (!$app->user->isGuest && $app->user->identity->profile->timezone) {
//                        $app->setTimeZone($app->user->identity->profile->timezone);
//                    }
//                });
            }
            //监听用户活动时间
            /** @var \yii\web\UserEvent $event */
            $app->on(\yii\web\Application::EVENT_AFTER_REQUEST, function ($event) use ($app) {
                if (!$app->user->isGuest && Yii::$app->has('queue')) {
                    //记录最后活动时间
                    Yii::$app->queue->push(new LastVisitJob(['user_id' => $app->user->identity->id, 'time' => time()]));
                }
            });
        }
        /**
         * 注册语言包
         */
        if (!isset($app->get('i18n')->translations['user*'])) {
            $app->get('i18n')->translations['user*'] = [
                'class' => PhpMessageSource::className(),
                'sourceLanguage' => 'en-US',
                'basePath' => __DIR__ . '/messages',
            ];
        }
    }
}