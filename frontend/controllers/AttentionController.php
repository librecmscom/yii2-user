<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;
use yuncms\tag\models\Tag;
use yuncms\user\models\User;
use yuncms\user\models\Attention;
use yuncms\user\Module;

/**
 * 关注操作
 * @package yuncms\user
 * @property Module $module
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
                    'tag' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['store', 'tag'],
                        'roles' => ['@'],
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new UnauthorizedHttpException(Yii::t('user', 'The request has not been applied because it lacks valid authentication credentials for the target resource.'));
                }
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
            $source = User::findOne($sourceId);
            $subject = $source->username;
        } else if ($sourceType == 'question' && Yii::$app->hasModule('question')) {
            $source = \yuncms\question\models\Question::findOne($sourceId);
            $subject = $source->title;
        } else if ($sourceType == 'article' && Yii::$app->hasModule('article')) {
            $source = \yuncms\article\models\Article::findOne($sourceId);
            $subject = $source->title;
        } else if ($sourceType == 'live' && Yii::$app->hasModule('live')) {
            $source = \yuncms\live\models\Stream::findOne($sourceId);
            $subject = $source->title;
        }//etc..

        if (!$source) {
            throw new NotFoundHttpException ();
        }

        /*再次关注相当于是取消关注*/
        $attention = Attention::findOne(['user_id' => Yii::$app->user->id, 'model' => get_class($source), 'model_id' => $sourceId]);
        if ($attention) {
            $attention->delete();
            if ($sourceType == 'user') {
                $source->extend->updateCounters(['followers' => -1]);
            } else {
                $source->updateCounters(['followers' => -1]);
            }
            return ['status' => 'unfollowed'];
        }


        $data = [
            'user_id' => Yii::$app->user->id,
            'model_id' => $sourceId,
            'model' => get_class($source),
        ];

        $attention = Attention::create($data);
        if ($attention) {
            switch ($sourceType) {
                case 'question' :
                    $this->module->notify(Yii::$app->user->id, $source->user_id, 'follow_question', $subject, $source->id);
                    $this->module->doing(Yii::$app->user->id, 'follow_question', get_class($source), $sourceId, $subject);
                    $source->updateCounters(['followers' => 1]);
                    break;
                case 'user':
                    $source->extend->updateCounters(['followers' => 1]);
                    $this->module->notify(Yii::$app->user->id, $sourceId, 'follow_user');
                    break;
                default:
                    $source->updateCounters(['followers' => 1]);
            }
            $attention->save();
        }
        return ['status' => 'followed'];
    }

    public function actionTag()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sourceId = Yii::$app->request->post('sourceId', null);
        $source = Tag::findOne($sourceId);
        if (!$source) {
            throw new NotFoundHttpException ();
        }
        $user = Yii::$app->user->identity;
        if ($user->hasTagValues($source->id)) {
            $user->removeTagValues($source->id);
            $user->save();
            return ['status' => 'unfollowed'];
        } else {
            $user->addTagValues($source->id);
            $user->save();
            return ['status' => 'followed'];
        }
    }
}