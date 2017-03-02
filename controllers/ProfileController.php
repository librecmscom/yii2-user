<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Doing;
use yuncms\user\models\User;
use yuncms\user\models\Visit;
use yuncms\user\models\Profile;

/**
 * ProfileController shows users profiles.
 *
 * @property \yuncms\user\Module $module
 */
class ProfileController extends Controller
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
                        'actions' => ['index'],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view', 'show', 'collect', 'followed', 'follower', 'credit', 'coin'],
                        'roles' => ['?', '@']
                    ]
                ]
            ]
        ];
    }

    /**
     * Redirects to current user's profile.
     *
     * @return \yii\web\Response
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
     * @param $username
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionView($username)
    {
        $model = $this->findModelByUsername($username);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $model->id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $model->id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $model->id]);
                $visit->save(false);
                //更新访客计数
                $model->user->userData->updateCounters(['views' => 1]);
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
     * @param integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->isGuest && Yii::$app->user->id != $id) {
            //记录访客
            if (($visit = Visit::findOne(['user_id' => Yii::$app->user->id, 'source_id' => $id])) == null) {
                $visit = new Visit(['user_id' => Yii::$app->user->id, 'source_id' => $id]);
                $visit->save(false);
                //更新访客计数
                $model->user->userData->updateCounters(['views' => 1]);
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
     * 我的关注
     * @param int $id
     * @return string
     */
    public function actionFollowed($id)
    {
        $model = $this->findModel($id);
        $dataProvider = $this->getDoingDataProvider($model->id);
        return $this->render('collect', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 查看收藏
     * @param int $id
     * @return string
     */
    public function actionCollect($id, $type = 'questions')
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getCollections()->andWhere(['model' => $type]),
        ]);
        return $this->render('collect', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
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
        /** @var User $userClass */
        $userClass = Yii::$app->user->identityClass;
        if (($model = $userClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $username
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModelByUsername($username)
    {
        /** @var User $userClass */
        $userClass = Yii::$app->user->identityClass;
        if (($model = $userClass::findByUsername($username)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
    }
}
