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

        $query = Profile::find()->with('user');

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
            'asc' => ['user_id' => SORT_ASC],
            'desc' => ['user_id' => SORT_DESC],
            'default' => SORT_DESC,
            'label' => 'Latest registration',
        ];
        $sort->attributes['updated_at'] = [
            'asc' => ['created_at' => SORT_ASC],
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
}