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
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Message;
use yuncms\user\models\MessageForm;
use yuncms\user\models\MessageSendForm;

/**
 * Class MessageController
 * @package yuncms\user\controllers
 */
class MessageController extends Controller
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
                        'actions' => ['index', 'send', 'view', 'delete'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['unread-messages'],
                        'roles' => ['@','?']
                    ],
                ],
            ],
        ];
    }

    /**
     * 收件箱
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Message::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);

        $query->where(['parent' => null])
            ->andWhere(['or', ['from_id' => Yii::$app->user->getId()], ['user_id' => Yii::$app->user->getId()]])
            ->orderBy(['id' => SORT_DESC])->with('user');

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * 发送私信
     * @return string|\yii\web\Response
     */
    public function actionSend()
    {
        $model = new MessageSendForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/user/message/index']);
        }
        return $this->render('send', ['model' => $model]);
    }

    /**
     * 查看短消息
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        //获取会话
        $model = $this->findModel($id);

        $dialogue = Message::find()->where(['id' => $model->id])->orWhere(['parent' => $model->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $dialogue
        ]);
        $dialogue->orderBy(['created_at' => SORT_ASC]);

        $form = new MessageForm();
        $form->parent = $model->id;
        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            return $this->refresh();
        }
        return $this->render('view', ['dataProvider' => $dataProvider, 'model' => $model, 'formModel' => $form]);
    }

    /**
     * 未读通知数目
     * @return array
     * @throws \Exception
     */
    public function actionUnreadMessages()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->user->isGuest) {
            return ['total' => 0];
        } else {
            $total = Message::getDb()->cache(function ($db) {
                return Message::find()->where(['user_id' => Yii::$app->user->id, 'status' => Message::STATUS_NEW])->count();
            }, 60);
            return ['total' => $total];
        }
    }

    /**
     * 获取会话
     * @param int $id
     * @return Message
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Message::find()
                ->where(['id' => $id, 'parent' => null])->andWhere(['or', ['from_id' => Yii::$app->user->id], ['user_id' => Yii::$app->user->id]])->limit(1)->one()) !== null
        ) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('user', 'The requested page does not exist.'));
        }
    }
}