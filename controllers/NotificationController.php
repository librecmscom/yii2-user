<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Notification;

/**
 * Class NotificationController
 * @package yuncms\user
 */
class NotificationController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'read-all' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'read-all', 'unread-notifications'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'read-all', 'unread-notifications'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 显示通知首页
     * @return string
     */
    public function actionIndex()
    {
        $query = Notification::find()->where(['to_user_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 标记通知未已读
     * @return \yii\web\Response
     */
    public function actionReadAll()
    {
        Notification::setReadAll(Yii::$app->user->id);
        Yii::$app->session->setFlash('Successful operation.');
        return $this->redirect(['index']);
    }

    /**
     * 未读通知数目
     * @return int
     * @throws \Exception
     */
    public function actionUnreadNotifications()
    {
        $response = Notification::getDb()->cache(function ($db) {
            return Notification::find()->where(['to_user_id' => Yii::$app->user->id, 'status' => Notification::STATUS_UNREAD])->count();
        }, 60);
        return $response;
    }
}