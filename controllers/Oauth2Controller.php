<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yuncms\user\models\Social;
use yuncms\user\models\Oauth2LoginForm;

/**
 * Oauth2 登录控制器
 * @package yuncms\user\controllers
 */
class Oauth2Controller extends Controller
{
    public function behaviors()
    {
        return [
            'oauth2Auth' => [
                'class' => 'yuncms\oauth2\filters\Authorize',
                'only' => ['authorize'],
            ],
        ];
    }

    public function actions()
    {
        return [
            /**
             * Returns an access token.
             */
            'token' => [
                'class' => 'yuncms\oauth2\actions\Token',
            ],
            /**
             * OPTIONAL
             * Third party oauth providers also can be used.
             */
            'back' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'successCallback'],
            ],
        ];
    }

    /**
     * Display login form, signup or something else.
     * AuthClients such as Google also may be used
     */
    public function actionAuthorize()
    {
        $model = new Oauth2LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if ($this->isOauthRequest) {
                $this->finishAuthorization();
            } else {
                return $this->goBack();
            }
        } else {
            return $this->render('authorize', [
                'model' => $model,
            ]);
        }
    }

    /**
     * OPTIONAL
     * Third party oauth callback sample
     * @param OAuth2 $client
     */
    public function successCallback($client)
    {
        $account = Social::find()->byClient($client)->one();
        if ($account === null) {
            $account = Social::create($client);
        }
        if ($account->user instanceof Yii::$app->user->id) {
            if ($account->user->isBlocked) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/oauth2/authorize']);
            } else {
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                if ($this->isOauthRequest) {
                    $this->finishAuthorization();
                }
            }
        } else {
            $this->action->successUrl = Url::to(['/user/oauth2/authorize']);
        }
    }
}