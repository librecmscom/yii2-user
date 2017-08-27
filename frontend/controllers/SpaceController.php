<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yuncms\doing\models\Doing;
use yuncms\user\models\User;
use yuncms\user\models\Visit;
use yuncms\tag\models\Tag;

/**
 * ProfileController shows users profiles.
 *
 * @property \yuncms\user\Module $module
 */
class SpaceController extends Controller
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
                        'actions' => ['index','tag'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'show'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Redirects to current user's profile.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = $this->findModel(Yii::$app->user->id);
        $dataProvider = $this->getDoingDataProvider($model->id);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Shows user's profile.
     * @param $slug
     * @return string
     */
    public function actionShow($slug)
    {
        $model = $this->findModelBySlug($slug);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $model->id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $model->id]);
                $visit->save(false);
                //更新访客计数
                $model->extend->updateCounters(['views' => 1]);
            } else {
                $visit->updateAttributes(['updated_at' => time()]);
            }
        }
        $dataProvider = $this->getDoingDataProvider($model->id);
        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Shows user's profile.
     *
     * @param int $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $id]);
                $visit->save(false);
                //更新访客计数
                $model->extend->updateCounters(['views' => 1]);
            } else {
                $visit->updateAttributes(['updated_at' => time()]);
            }
        }

        $dataProvider = $this->getDoingDataProvider($model->id);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 关注某tag
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionTag()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $sourceId = Yii::$app->request->post('sourceId', null);
        $source = Tag::findOne($sourceId);
        if (!$source) {
            throw new NotFoundHttpException ();
        }
        /** @var \yuncms\user\models\User $user */
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

    /**
     * 获取个人动态
     * @param int $user_id
     * @return ActiveDataProvider
     */
    protected function getDoingDataProvider($user_id)
    {
        $query = Doing::find()->where(['user_id' => $user_id])->orderBy(['created_at' => SORT_DESC]);
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => 15,
            ]
        ]);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $slug
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelBySlug($slug)
    {
        if (($model = User::findBySlug($slug)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}
