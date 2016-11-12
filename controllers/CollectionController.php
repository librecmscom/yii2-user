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
        $sourceType = Yii::$app->request->post('sourceType');
        $sourceId = Yii::$app->request->post('sourceId');
        /** @var null|\yii\db\ActiveRecord $source */
        $source = null;

        //此处获取要收藏的模型

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        /*不能多次收藏*/
        $userCollect = Collection::findOne(['user_id' => Yii::$app->user->id, 'source_type' => get_class($source), 'source_id' => $sourceId]);
        if ($userCollect) {
            $userCollect->delete();
            $source->updateCounters(['collections' => -1]);
            return ['status' => 'uncollect'];
        }

        $data = [
            'user_id' => Yii::$app->user->id,
            'source_id' => $sourceId,
            'source_type' => get_class($source),
            'subject' => $subject,
        ];

        $collect = new Collection($data);
        if ($collect) {
            $source->updateCounters(['collections' => 1]);
            $collect->save();
        }
        return ['status' => 'collected'];
    }
}