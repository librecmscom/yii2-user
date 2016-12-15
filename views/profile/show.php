<?php
use yii\helpers\Html;
use yuncms\user\models\Profile;

/**
 * @var \yii\web\View $this
 * @var \yuncms\user\models\Profile $model
 */
$this->title = empty($model->name) ? Html::encode($model->user->username) : Html::encode($model->name);
$this->context->layout = 'space';
$this->params['profile'] = $model;
?>
