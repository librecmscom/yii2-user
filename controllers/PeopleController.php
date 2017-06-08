<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yuncms\tag\models\Tag;
use yuncms\user\models\User;
use yuncms\user\models\Profile;
use yuncms\user\Module;

/**
 * Class PeopleController
 * @property Module $module
 * @package yuncms\user\controllers
 */
class PeopleController extends Controller
{
    /**
     * 用户列表页
     */
    public function actionIndex()
    {
        Url::remember('', 'actions-redirect');

        //取Get参数
        $params = Yii::$app->request->get();

        $query = User::find()->with('profile');

        //搜索
        if (isset($params['q'])) {
            $query->name(trim($params['q']));
        }

        //只看妹子
        if (isset($params['female'])) {
            $query->female();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        //开始排序
        $sort = $dataProvider->getSort();
        $sort->enableMultiSort = false;

        $sort->attributes['created_at'] = [
            'asc' => ['created_at' => SORT_ASC],
            'desc' => ['created_at' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Latest registration',
        ];
        $sort->attributes['updated_at'] = [
            'asc' => ['updated_at' => SORT_ASC],
            'desc' => ['updated_at' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Recently updated',
        ];

        return $this->render('index', [
            'total' => $this->module->getTotal(60),
            'params' => $params,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 标签
     * @param string $tag
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionTag($tag)
    {
        Url::remember('', 'actions-redirect');
        if (($model = Tag::findOne(['name' => $tag])) !== null) {
            $query = User::find()->anyTagValues($tag, 'name');
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            return $this->render('tag', [
                'model' => $model,
                'dataProvider' => $dataProvider
            ]);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 获取用户总数
     * @return array
     */
    public function actionTotal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['total' => $this->module->getTotal(60)];
    }

    /**
     * 获取今日注册总数
     * @return array
     */
    public function actionTodayTotal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['total' => $this->module->getTodayTotal(60)];
    }

    /**
     * 获取获取今日活跃用户总数
     * @return array
     */
    public function actionTodayActivityTotal()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['total' => $this->module->getTodayActivityTotal(60)];
    }


}