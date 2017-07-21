<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Rest;

/**
 * Class AccessKeyController
 * @package yuncms\user
 */
class AccessKeyController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Rest models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Rest::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Blocks the user.
     *
     * @param int $id
     *
     * @return Response
     */
    public function actionBlock($id)
    {
        $user = $this->findModel($id);
        if ($user->isBlocked()) {
            $user->unblock();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been unblocked'));
        } else {
            $user->block();
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'User has been blocked'));
        }
        return $this->redirect(Url::previous('actions-redirect'));
    }

    /**
     * Creates a new Rest model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Rest();
        $model->save();
        Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Successful operation'));
        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Rest model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isAuthor()) {
            $model->delete();
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Rest model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return Rest the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Rest::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('user', 'The requested page does not exist.'));
        }
    }
}