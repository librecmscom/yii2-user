<?php

namespace yuncms\user\controllers;

use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yuncms\user\models\Withdrawals;
use yuncms\user\models\WithdrawalsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * WithdrawalsController implements the CRUD actions for Withdrawals model.
 */
class WithdrawalsController extends Controller
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
                        'actions' => ['index', 'view', 'create'],
                        'roles' => ['@']
                    ],
                ]
            ],
        ];
    }

    /**
     * Lists all Withdrawals models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WithdrawalsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Withdrawals model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Withdrawals model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $currency
     * @return mixed
     */
    public function actionCreate($currency)
    {
        $model = new Withdrawals();
        $model->currency = $currency;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model
            ]);
        }
    }

    /**
     * Finds the Withdrawals model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Withdrawals the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Withdrawals::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
