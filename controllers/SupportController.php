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
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yuncms\user\models\Support;

/**
 * Class SupportController
 * @package yuncms\user\controllers
 */
class SupportController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'store' => ['POST'],
                    'check' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['check', 'store'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionCheck()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sourceType = Yii::$app->request->post('sourceType');
        $sourceId = Yii::$app->request->post('sourceId');

        if ($sourceType === 'answer' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Answer::findOne($sourceId);
        }

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        $support = Support::findOne(['user_id' => Yii::$app->user->id, 'source_type' => get_class($source), 'source_id' => $sourceId]);
        if ($support) {
            return ['status' => 'failed'];
        }
        return ['status' => 'success'];
    }

    public function actionStore()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sourceType = Yii::$app->request->post('sourceType');
        $sourceId = Yii::$app->request->post('sourceId');
        /** @var null|\yii\db\ActiveRecord $source */
        $source = null;
        if ($sourceType == 'user') {
            /** @var \yii\db\ActiveRecord $userClass */
            $userClass = Yii::$app->user->identityClass;
            $source = $userClass::findOne($sourceId);
        } else if ($sourceType == 'question' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Question::findOne($sourceId);
        } else if ($sourceType == 'answer' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Answer::findOne($sourceId);
        } else if ($sourceType == 'article' && Yii::$app->hasModule('article')) {
            $source = \yuncms\article\models\Article::findOne($sourceId);
        }
        //etc..

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        $support = Support::findOne(['user_id' => Yii::$app->user->id, 'source_type' => get_class($source), 'source_id' => $sourceId]);
        if ($support) {
            return ['status' => 'supported'];
        }

        $data = [
            'user_id' => Yii::$app->user->id,
            'source_id' => $sourceId,
            'source_type' => get_class($source),
        ];

        $support = new Support($data);
        if ($support) {
            $source->updateCounters(['supports' => 1]);
        }
        $support->save();
        return ['status' => 'success'];
    }
}