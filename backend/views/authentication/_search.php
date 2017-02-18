<?php

use yii\helpers\Html;
use yuncms\admin\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\user\backend\models\AuthenticationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authentication-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'real_name') ?>

    <?= $form->field($model, 'id_card') ?>

    <?= $form->field($model, 'id_card_image') ?>

    <?= $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'failed_reason') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
