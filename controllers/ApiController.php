<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\rest\Controller;

/**
 * Class ApiController
 * @package yuncms\user
 */
class ApiController extends Controller
{

    public function behaviors()
    {
        return [
            /**
             * Performs authorization by token
             */
            'tokenAuth' => [
                'class' => 'yuncms\oauth2\TokenAuth',
            ],
        ];
    }

    public function actionMe()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $user = Yii::$app->user->identity;
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }
}