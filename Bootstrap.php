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
                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules' => $module->urlRules,
                ];
                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }
                $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);
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