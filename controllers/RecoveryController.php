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
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Token;
use yuncms\user\models\RecoveryForm;

/**
 * RecoveryController manages password recovery process.
 *
 * @property \yuncms\user\Module $module
 */
class RecoveryController extends Controller
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
                        'actions' => ['request', 'reset'],
                        'roles' => ['?']
                    ]
                ]
            ]
        ];
    }

    /**
     * Shows page where user can request password recovery.
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRequest()
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }
        /** @var RecoveryForm $model */
        $model = new RecoveryForm(['scenario' => 'request']);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post()) && $model->sendRecoveryMessage()) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'Recovery message sent'),
                'module' => $this->module
            ]);
        }
        return $this->render('request', ['model' => $model]);
    }

    /**
     * Displays page where user can reset password.
     *
     * @param integer $id
     * @param string $code
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        if (!$this->module->enablePasswordRecovery) {
            throw new NotFoundHttpException();
        }
        /** @var Token $token */
        $token = Token::findOne(['user_id' => $id, 'code' => $code, 'type' => Token::TYPE_RECOVERY]);
        if ($token === null || $token->isExpired || $token->user === null) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Recovery link is invalid or expired. Please try requesting a new one.'));
            return $this->render('/message', [
                'title' => Yii::t('user', 'Invalid or expired link'),
                'module' => $this->module
            ]);
        }
        /** @var RecoveryForm $model */
        $model = new RecoveryForm(['scenario' => 'reset']);
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->getRequest()->post()) && $model->resetPassword($token)) {
            return $this->render('/message', [
                'title' => Yii::t('user', 'Password has been changed'),
                'module' => $this->module
            ]);
        }

        return $this->render('reset', ['model' => $model]);
    }
}
