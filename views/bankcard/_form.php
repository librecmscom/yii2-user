<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\user\models\BankCard */
/* @var $form yii\widgets\ActiveForm */
?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>

<?= $form->field($model, 'bank')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bank_city')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bank_username')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'bankcard_number')->textInput(['maxlength' => true]) ?>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>


