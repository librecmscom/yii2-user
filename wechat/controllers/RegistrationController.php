<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\wechat\controllers;

use Yii;
use yii\web\Controller;
use yuncms\user\models\User;
use yuncms\user\models\Social;
use yii\filters\AccessControl;
use yuncms\user\models\ResendForm;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\NotFoundHttpException;
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
     * Displays the registration page.
     * After successful registration if enableConfirmation is enabled shows info message otherwise redirects to home page.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRegister()
    {
        return $this->redirect(['/user/security/auth', 'authclient' => 'wechat']);
    }

    /**
     * Displays page where user can create new account that will be connected to social account.
     *
     * @param string $code
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionConnect($code)
    {
        $account = Social::find()->byCode($code)->one();

        if ($account === null || $account->getIsConnected()) {
            throw new NotFoundHttpException();
        }

        /** @var User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'connect',
            'username' => $account->username,
            'email' => $account->email,
        ]);

        if ($user->load(Yii::$app->request->post()) && $user->create()) {
            $account->connect($user);
            Yii::$app->user->login($user, $this->module->rememberFor);
            return $this->goBack();
        }

        return $this->render('connect', [
            'model' => $user,
            'account' => $account,
        ]);
    }

    /**
     * Confirms user's account. If confirmation was successful logs the user and shows success message. Otherwise
     * shows error message.
     *
     * @param integer $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionConfirm($id, $code)
    {
        $user = User::findOne($id);
        if ($user === null || $this->module->enableConfirmation == false) {
            return $this->goBack();
        }
        $user->attemptConfirmation($code);
        return $this->redirect(['/user/setting/profile']);
    }

    /**
     * Displays page where user can request new confirmation token. If resending was successful, displays message.
     *
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionResend()
    {
        if ($this->module->enableConfirmation == false) {
            return $this->goBack();
        }
        /** @var ResendForm $model */
        $model = new ResendForm();
        if (Yii::$app->request->getIsAjax() && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->resend()) {
            return $this->redirect(['/user/setting/profile']);
        }
        return $this->render('resend', ['model' => $model]);
    }
}
