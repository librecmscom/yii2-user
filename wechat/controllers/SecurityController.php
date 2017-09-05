<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\controllers;


use Yii;

use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\authclient\ClientInterface;
use yii\web\NotFoundHttpException;

use xutl\wechat\oauth\OAuth;
use xutl\wechat\oauth\AuthAction;

use yuncms\user\Module;
use yuncms\user\models\User;
use yuncms\user\models\Wechat;
use yuncms\user\wechat\models\ConnectForm;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 */
class SecurityController extends Controller
{
    protected $rememberFor;
    public function init()
    {
        parent::init();
        $this->rememberFor = Yii::$app->settings->get('rememberFor', 'user');
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'connect'],
                        'roles' => ['?','@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@']
                    ],
                ]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'login' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'authenticate']
            ]
        ];
    }

    /**
     * 退出用户后重定向到主页
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout();
        Yii::$app->user->setReturnUrl(Yii::$app->request->getReferrer());
        return $this->goBack();
    }

    /**
     * 通过微信登录，如果用户不存在，将创建或绑定用户
     * @param OAuth $client
     */
    public function authenticate(OAuth $client)
    {
        $account = Wechat::find()->byClient($client)->one();
        if ($account === null) {
            $account = Wechat::create($client);
        }
        if ($account->user instanceof User) {
            if ($account->user->isBlocked) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } else {
                Yii::$app->user->login($account->user, $this->rememberFor);
                $this->action->successUrl = Yii::$app->getUser()->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }
    }

    /**
     * 将微信用户连接到系统内用户
     *
     * @param string $code
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionConnect($code)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goBack();
        }
        $account = Wechat::find()->byCode($code)->one();

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }
        $model = new ConnectForm(['wechat' => $account]);
        if ($model->load(Yii::$app->request->post()) && $model->connect()) {
            return $this->goBack(Yii::$app->getHomeUrl());
        }
        return $this->render('connect', [
            'model' => $model,
        ]);
    }
}
