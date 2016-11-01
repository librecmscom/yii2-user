<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;

/**
 * Class ChangyanController
 * @package yuncms\user\controllers
 */
class ChangyanController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 畅言获取用户信息接口
     * @param string $callback
     * @return array
     */
    public function actionInfo($callback)
    {
        Yii::$app->response->format = Response::FORMAT_JSONP;
        if (Yii::$app->user->isGuest) {
            return ['callback' => $callback, 'data' => ['is_login' => 0]];
        }
        return [
            'callback' => $callback,
            'data' => [
                'is_login' => 1,
                'user' => [
                    'img_url' => Yii::$app->user->identity->getAvatar(),
                    'nickname' => Yii::$app->user->identity->username,
                    'profile_url' => Url::to(['/user/profile/show', 'id' => Yii::$app->user->id]),
                    'user_id' => Yii::$app->user->id,
                    'sign' => md5('123456'),
                ],
            ]
        ];
    }

    /**
     * 畅言退出接口
     * @param string $callback
     * @return array
     */
    public function actionLogout($callback)
    {
        Yii::$app->getUser()->logout();
        Yii::$app->response->format = Response::FORMAT_JSONP;
        return [
            'callback' => $callback,
            'data' => [
                'code' => 1,
                'reload_page' => 1,
                'js_src' => '',
            ]
        ];

    }
}