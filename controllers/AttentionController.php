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
use yuncms\user\models\Attention;

/**
 * Class AttentionController
 * @property \yuncms\user\Module $module
 * @package yuncms\user
 */
class AttentionController extends Controller
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
            $subject = $source->username;
        } else if ($sourceType == 'question' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Question::findOne($sourceId);
            $subject = $source->title;
        }//etc..

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        /*再次关注相当于是取消关注*/
        $attention = Attention::findOne(['user_id' => Yii::$app->user->id, 'source_type' => get_class($source), 'source_id' => $sourceId]);
        if ($attention) {
            $attention->delete();
            if ($sourceType == 'user') {
                $source->userData->updateCounters(['followers' => -1]);
            } else {
                $source->updateCounters(['followers' => -1]);
            }
            return ['status' => 'unfollowed'];
        }

        $data = [
            'user_id' => Yii::$app->user->id,
            'source_id' => $sourceId,
            'source_type' => get_class($source),
        ];

        $attention = new Attention($data);
        if ($attention) {
            switch ($sourceType) {
                case 'user':
                    $source->userData->updateCounters(['followers' => 1]);
                    break;
                default:
                    $source->updateCounters(['followers' => 1]);
            }
            $attention->save();
        }
        return ['status' => 'followed'];
    }
}