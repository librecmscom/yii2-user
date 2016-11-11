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
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('user') && ($module = $app->getModule('user')) instanceof Module) {
            if ($app instanceof \yii\console\Application) {
                $app->controllerMap['user'] = [
                    'class' => 'yuncms\user\console\UserController',
                ];
            } else if ($module instanceof \yuncms\user\backend\Module) {

            } elseif ($module instanceof Module) {//前台判断放最后
                Yii::$container->set('yii\web\User', [
                    'enableAutoLogin' => true,
                    'loginUrl' => ['/user/security/login'],
                    'identityClass' => '\yuncms\user\models\User',
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

                //监听用户活动时间
                /** @var \yii\web\UserEvent $event */
                $app->on(\yii\web\Application::EVENT_AFTER_REQUEST, function ($event) use ($app) {
                    if (!$app->user->isGuest) {
                        //记录最后活动时间
                        $app->user->identity->userData->updateAttributes(['last_visit' => time()]);
                    }
                });
            }
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