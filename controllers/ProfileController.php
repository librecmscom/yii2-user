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
use yuncms\user\models\Coin;
use yuncms\user\models\Credit;
use yuncms\user\models\Visit;
use yuncms\user\models\Collection;

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
                        'actions' => ['view', 'show', 'collected', 'attention', 'follower', 'credit', 'coin'],
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
    public function actionShow($username)
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
    public function actionView($id)
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
     * 我的金币
     * @param int $id
     * @return string
     */
    public function actionCoin($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Coin::find()->where(['user_id' => $model->id])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('coin', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 我的经验
     * @param int $id
     * @return string
     */
    public function actionCredit($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => Credit::find()->where(['user_id' => $model->id])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('credit', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * 我的粉丝
     * @param int $id
     * @return string
     */
    public function actionFollower($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getFans()->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('follower', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    public $attentionClassMaps = [
        'questions' => 'yuncms\question\models\Question',
        'users' => 'yuncms\user\models\User',
        'lives' => 'yuncms\live\models\Stream'
    ];

    /**
     * 我的关注
     * @param int $id
     * @param string $type
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAttention($id, $type)
    {
        $model = $this->findModel($id);
        if (!isset($this->attentionClassMaps[$type])) {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getAttentions()->andWhere(['model' => $this->attentionClassMaps[$type]])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('attention', [
            'model' => $model,
            'type' => $type,
            'dataProvider' => $dataProvider
        ]);
    }

    public $collectionClassMaps = [
        'questions' => 'yuncms\question\models\Question',
        'articles' => 'yuncms\article\models\Article',
        'lives' => 'yuncms\live\models\Stream',
    ];

    /**
     * 查看收藏
     * @param int $id
     * @param string $type 类别
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionCollected($id, $type)
    {
        $model = $this->findModel($id);
        if (!isset($this->collectionClassMaps[$type])) {
            throw new NotFoundHttpException(Yii::t('yii', 'The requested page does not exist.'));
        }
        $query = $model->getCollections()->andWhere(['model' => $this->collectionClassMaps[$type]])->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('collected', [
            'model' => $model,
            'type' => $type,
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
