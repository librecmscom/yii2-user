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
        if (!Yii::$app->user->isGuest) {//过滤掉自己
            $query->andWhere(['<>', 'user_id', Yii::$app->user->id]);
        }

        if (isset($params['q'])) {
            $query->name(trim($params['q']));
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'params' => $params,
            'dataProvider' => $dataProvider,
        ]);
    }
}