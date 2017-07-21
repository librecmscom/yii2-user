<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yuncms\user\models\Doing;

/**
 * 发现
 * @package yuncms\user
 */
class DoingController extends Controller
{
    public function actionIndex()
    {
        $query = Doing::find()->orderBy(['created_at' => SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pagesize' => '10',
            ]
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
}