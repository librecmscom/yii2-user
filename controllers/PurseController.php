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
use yii\data\ActiveDataProvider;
use yuncms\user\models\Purse;

/**
 * ProfileController shows users profiles.
 *
 * @property \yuncms\user\Module $module
 */
class PurseController extends Controller
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
                    ]
                ]
            ]
        ];
    }

    /**
     * 显示钱包首页
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Purse::find()->where(['user_id' => Yii::$app->user->id])->orderBy(['created_at' => SORT_DESC]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }


}