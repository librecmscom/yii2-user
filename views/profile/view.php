<?php
use yii\helpers\Html;
use yuncms\user\models\Profile;

/**
 * 这个文件和show是一样的，区别是一个是用ID来访问用户主页，一个是用用户名来访问用户主页
 * @var \yii\web\View $this
 * @var \yuncms\user\models\Profile $model
 */
$this->title = empty($model->name) ? Html::encode($model->user->username) : Html::encode($model->name);
$this->context->layout = 'space';
$this->params['profile'] = $model;
?>
