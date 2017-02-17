<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\controllers;

use Yii;
use yii\web\Response;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yuncms\user\models\Authentication;

/**
 * Class AuthenticationController
 * @package yuncms\user\controllers
 */
class AuthenticationController extends Controller
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
                        'actions' => ['index', 'update'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 认证首页
     * @return string
     */
    public function actionIndex()
    {
        if (($model = Yii::$app->user->identity->authentication) == null) {
            /** @var Authentication $model */
            $model = Yii::createObject([
                'class' => Authentication::className(),
                'scenario' => 'create',
            ]);
            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->load(Yii::$app->request->post()) && $model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('index', [
            'model' => $model
        ]);
    }

    /**
     * 更新实名认证
     * @return string|Response
     */
    public function actionUpdate()
    {
        /** @var Authentication $model */
        if (($model = Yii::$app->user->identity->authentication) == null) {
            return $this->redirect(['index']);
        }
        $model->scenario = 'update';
        if (Yii::$app->request->isPost) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);

    }
}
