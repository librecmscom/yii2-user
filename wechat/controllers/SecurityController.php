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
use yii\widgets\ActiveForm;
use yii\filters\AccessControl;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yuncms\user\Module;
use yuncms\user\models\User;
use yuncms\user\models\Social;
use yuncms\user\models\LoginForm;


/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 */
class SecurityController extends Controller
{
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
                        'actions' => ['login', 'auth', 'blocked'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['login', 'auth'],
                        'roles' => ['@']
                    ]
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
            'auth' => [
                'class' => AuthAction::className(),
                // 如果用户未登录，将尝试登录，否则将尝试连接到用户的社交账户。
                'successCallback' => Yii::$app->user->getIsGuest() ? [$this, 'authenticate'] : [$this, 'connect']
            ]
        ];
    }

    /**
     * 登录
     * @return array|string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'You are already logged in.'));
            return $this->goHome();
        }
        if (Yii::$app->request->isGet) {
            Yii::$app->user->setReturnUrl(Yii::$app->request->getReferrer());
        }
        //转到微信登录
        return $this->redirect(['/user/security/auth', 'authclient' => 'wechat']);
    }

    /**
     * Tries to authenticate user via social network. If user has already used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *
     * @param ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
    {
        $account = Social::find()->byClient($client)->one();
        if ($account === null) {
            $account = Social::create($client);
        }
        if ($account->user instanceof User) {
            if ($account->user->isBlocked) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } else {
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = Yii::$app->getUser()->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }
    }

    /**
     * 尝试将社交账号连接到用户
     *
     * @param ClientInterface $client
     */
    public function connect(ClientInterface $client)
    {
        /**
         * @var Social $account
         */
        $account = new Social();
        $account->connectWithUser($client);
        $this->action->successUrl = Url::to(['/user/setting/networks']);
    }
}
