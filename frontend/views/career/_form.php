<?php

use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\bootstrap\ActiveForm;

/* @var yii\web\View $this */
/* @var yuncms\user\models\Career $model */
/* @var yii\widgets\ActiveForm $form */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'position') ?>
<?= $form->field($model, 'city'); ?>
<?= $form->field($model, 'start_at')->widget(DatePicker::classname(), [
    'dateFormat' => 'yyyy-MM',
    'options' => ['class' => 'form-control'],
    'clientOptions' => ['changeMonth' => true, 'changeYear' => true]
]) ?>
<?= $form->field($model, 'end_at')->widget(DatePicker::classname(), [
    'dateFormat' => 'yyyy-MM',
    'options' => ['class' => 'form-control'],
    'clientOptions' => ['changeMonth' => true, 'changeYear' => true]
]) ?>
<?= $form->field($model, 'description')->textarea() ?>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

<?php ActiveForm::end(); ?>