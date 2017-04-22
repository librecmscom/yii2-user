<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\user\backend\models\AuthenticationSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="authentication-search  pull-right">

    <?php $form = ActiveForm::begin([
        'layout' => 'inline',
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'user_id', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('user_id'),
        ],
    ]) ?>

    <?= $form->field($model, 'real_name', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('real_name'),
        ],
    ]) ?>

    <?= $form->field($model, 'id_card', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('id_card'),
        ],
    ]) ?>

    <?php // echo  $form->field($model, 'id_card_image') ?>

    <?= $form->field($model, 'status', [
        'inputOptions' => [
            'placeholder' => $model->getAttributeLabel('status'),
        ],
    ]) ?>

    <?php // echo $form->field($model, 'failed_reason') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
