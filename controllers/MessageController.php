<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\controllers;

use Yii;
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
     * @param null|string $username 给指定用户发信
     * @return string|\yii\web\Response
     */
    public function actionSend($username = null)
    {
        $model = new MessageSendForm();
        if (!empty($username)) {
            $model->username = $username;
        }
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
        $conversation = $this->findModel($id);

        $query = $conversation->getMessages();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $query->orderBy(['created_at' => SORT_ASC]);

        $model = new MessageForm();
        $model->parent = $conversation->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->refresh();
        }
        return $this->render('view', ['dataProvider' => $dataProvider, 'conversation' => $conversation, 'model' => $model]);
    }

    /**
     * 获取会话
     * @param int $id
     * @return Message
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Message::find()
                ->where(['id' => $id, 'parent' => null])->andWhere(['or', ['from_id' => Yii::$app->user->id], ['user_id' => Yii::$app->user->id]])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException (Yii::t('user', 'The requested page does not exist.'));
        }
    }
}