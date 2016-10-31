<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\widgets\ActiveForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Career;

class ChangyanController extends Controller
{
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
                    'img_url' => Yii::$app->user->id->getAvatar(),
                    'nickname' => Yii::$app->user->id->username,
                    'profile_url' => '',
                    'user_id' => Yii::$app->user->id,
                    'sign' => hash_hmac('img_url={img_url}&nickname={nickname}&profile_url={profile_url}&user_id={user_id}'),
                ],
            ]
        ];
    }

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