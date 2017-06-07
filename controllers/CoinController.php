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
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Coin;
use yuncms\user\models\Recharge;
use yuncms\payment\models\Payment;

/**
 * Class CoinController
 * @package yuncms\user
 */
class CoinController extends Controller
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
                        'actions' => ['index','recharge'],
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
            'query' => Coin::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]),
            'pagination' => [
                'defaultPageSize' => 10,
                'pageSize' => 10
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 积分充值
     * @return array|string
     */
    public function actionRecharge()
    {
        $model = new Recharge();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $payment = new Payment([
                'currency' => $model->currency,
                'money' => $model->money,
                'name' => Yii::t('user', 'Coin Recharge'),
                'gateway' => $model->gateway,
                'pay_type' => Payment::TYPE_MWEB,
                'model_id' => $model->id,
                'model' => get_class($model),
                'return_url' => Url::to(['/user/coin/index'], true),
            ]);
            if ($payment->save()) {
                $model->link('payment', $payment);
                return $this->redirect(['/payment/default/pay', 'id' => $payment->id]);
            }
        }
        return $this->render('recharge', [
            'model' => $model,
        ]);
    }

}