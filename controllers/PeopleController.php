<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Profile;

/**
 * Class PeopleController
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

        $query = Profile::find()->with('user');
        //过滤掉自己
        if (!Yii::$app->user->isGuest) {
            $query->andWhere(['<>', 'user_id', Yii::$app->user->id]);
        }

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
            'label' => '最新加入',
        ];
        $sort->attributes['updated_at'] = [
            'asc' => ['created_at' => SORT_ASC],
            'desc' => ['updated_at' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => '最近更新',
        ];

        return $this->render('index', [
            'params' => $params,
            'dataProvider' => $dataProvider,
        ]);
    }
}