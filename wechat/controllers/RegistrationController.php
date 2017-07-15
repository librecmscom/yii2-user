<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\controllers;

use Yii;
use yii\web\Controller;
use yuncms\user\helpers\Password;
use yuncms\user\models\User;
use yuncms\user\models\Social;
use yii\filters\AccessControl;
use yuncms\user\models\ResendForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Wechat;
use yuncms\user\wechat\models\ConnectForm;
use yuncms\user\wechat\models\RegistrationForm;

/**
 * RegistrationController is responsible for all registration process, which includes registration of a new account,
 * resending confirmation tokens, email confirmation and registration via social networks.
 *
 * @property \yuncms\user\Module $module
 *
 */
class RegistrationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'minLength' => 4,
                'maxLength' => 5,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['register', 'connect', 'captcha'],
                        'roles' => ['?']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['register', 'confirm', 'resend'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    /**
     * 将微信用户连接到系统内用户
     *
     * @param string $code
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConnect($code)
    {
        $account = Wechat::find()->byCode($code)->one();

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }

        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'wechat_register',
        ]);

        if ($user->load(Yii::$app->request->post())) {
            /** @var User $connectUser */
            $connectUser = User::find()->orWhere(['username' => $user->username, 'email' => $user->email])->one();
            if ($connectUser !== null && Password::validate($user->password,$connectUser->password_hash)) {
                $account->connect($connectUser);
                Yii::$app->user->login($connectUser, $this->module->rememberFor);
                return $this->goBack();
            } else {
                $user->create();
                $account->connect($user);
                Yii::$app->user->login($user, $this->module->rememberFor);
                return $this->goBack();
            }
        }

        return $this->render('connect', [
            'model' => $user,
            'account' => $account,
        ]);
    }
}
