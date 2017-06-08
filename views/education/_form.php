<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdminForm */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>

<?= $form->field($model, 'school') ?>

<?= $form->field($model, 'department') ?>

<?= $form->field($model, 'degree')->dropDownList(['大专' => '大专', '本科' => '本科', '硕士' => '硕士', '博士' => '博士', '其他' => '其他']); ?>

<?= $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>