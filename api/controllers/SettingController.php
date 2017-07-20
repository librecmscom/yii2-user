<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yuncms\oauth2\Controller;
use yuncms\user\models\Profile;
use yii\web\ServerErrorHttpException;

/**
 * Class SettingController
 * @package yuncms\user\api\controllers
 */
class SettingController extends Controller
{
    /**
     * 获取或修改用户资料
     *
     * @return string|\yii\web\Response
     */
    public function actionProfile()
    {
        /** @var \yuncms\user\models\Profile $model */
        $model = Yii::$app->user->identity->profile;
        if ($model->load(Yii::$app->request->getBodyParams(), '')) {
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        }
        return $model;
    }

    /**
     * Show portrait setting form
     * @return \yii\web\Response|string
     */
    public function actionAvatar()
    {
        $model = new AvatarForm();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('user', 'Your avatar has been updated'));
        }
        return $this->render('avatar', [
            'model' => $model,
        ]);
    }
}
