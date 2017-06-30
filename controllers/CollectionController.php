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
use yii\web\UnauthorizedHttpException;
use yuncms\user\models\Collection;
use yuncms\user\models\User;

/**
 * Class CollectionController
 * @property \yuncms\user\Module $module
 * @package yuncms\user
 */
class CollectionController extends Controller
{
    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'store' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['store'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new UnauthorizedHttpException(Yii::t('user', 'The request has not been applied because it lacks valid authentication credentials for the target resource.'));
                }
            ],
        ];
    }

    /**
     * 添加收藏
     * @return array
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionStore()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Yii::$app->request->post('sourceType');
        $modelId = Yii::$app->request->post('sourceId');
        /** @var null|\yii\db\ActiveRecord $source */
        $source = null;

        if ($model == 'user') {
            $userClass = Yii::$app->user->identityClass;
            $source = $userClass::find()->with('userData')->where(['id' => $modelId])->one();
            $subject = $source->username;
        } else if ($model == 'question' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Question::findOne($modelId);
            $subject = $source->title;
        } else if ($model == 'article' && Yii::$app->hasModule('article')) {
            $source = \yuncms\article\models\Article::findOne($modelId);
            $subject = $source->title;
        } else if ($model == 'live' && Yii::$app->hasModule('live')) {
            $source = \yuncms\live\models\Stream::findOne($modelId);
            $subject = $source->title;
        } else if ($model == 'note' && Yii::$app->hasModule('note')) {
            $source = \yuncms\note\models\Note::findOne($modelId);
            $subject = $source->title;
        }

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        /*不能多次收藏*/
        $userCollect = Collection::findOne(['user_id' => Yii::$app->user->id, 'model' => get_class($source), 'model_id' => $modelId]);
        if ($userCollect) {
            $userCollect->delete();
            if ($model == 'user') {
                $source->userData->updateCounters(['collections' => -1]);
            } else {
                $source->updateCounters(['collections' => -1]);
            }

            return ['status' => 'uncollect'];
        }

        $data = [
            'user_id' => Yii::$app->user->id,
            'model_id' => $modelId,
            'model' => get_class($source),
            'subject' => $subject,
        ];

        $collect = Collection::create($data);
        if ($collect) {
            if ($model == 'user') {
                $source->userData->updateCounters(['collections' => 1]);
            } else {
                $source->updateCounters(['collections' => 1]);
            }
            $collect->save();
        }
        return ['status' => 'collected'];
    }
}