<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Visit;
use yuncms\user\models\Profile;

/**
 * ProfileController shows users profiles.
 *
 * @property \yuncms\user\Module $module
 */
class ProfileController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'show'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        return $this->render('show', ['model' => $this->findModel(Yii::$app->user->id)]);
    }

    /**
     * Shows user's profile.
     * @param $username
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($username)
    {
        $model = $this->findModelByUsername($username);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->user_id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $model->user_id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $model->user_id]);
                $visit->save(false);
                //更新访客计数
                $model->user->userData->updateCounters(['views' => 1]);
            } else {
                $visit->updateAttributes(['updated_at' => time()]);
            }
        }
        return $this->render('view', ['model' => $model]);
    }

    /**
     * Shows user's profile.
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $id]);
                $visit->save(false);
                //更新访客计数
                $model->user->userData->updateCounters(['views' => 1]);
            } else {
                $visit->updateAttributes(['updated_at' => time()]);
            }
        }
        return $this->render('show', ['model' => $model]);
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $userClass = Yii::$app->user->identityClass;
        if (($model = $userClass::findOne($id)) !== null) {
            return $model->profile;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }

    /**
     * Finds the Profile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $username
     * @return Profile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUsername($username)
    {
        $userClass = Yii::$app->user->identityClass;
        if (($model = $userClass::findByUsername($username)) !== null) {
            return $model->profile;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}
