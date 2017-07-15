<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\authclient\ClientInterface;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yuncms\user\models\ConnectForm;
use yuncms\user\Module;
use yuncms\user\models\User;
use yuncms\user\models\Wechat;
use xutl\wechat\oauth\AuthAction;

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
                        'actions' => ['login', 'blocked'],
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
            'login' => [
                'class' => AuthAction::className(),
                'successCallback' => [$this, 'authenticate']
            ]
        ];
    }

    /**
     * 通过微信登录，如果用户不存在，将创建或绑定用户
     *
     * @param ClientInterface $client
     */
    public function authenticate(ClientInterface $client)
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
                Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = Yii::$app->getUser()->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }
    }
}
