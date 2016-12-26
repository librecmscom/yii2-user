<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\jui\DatePicker;

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

<?= $form->field($model, 'name') ?>
<?= $form->field($model, 'position') ?>
<?= $form->field($model, 'city'); ?>
<?= $form->field($model, 'start_at')->widget(DatePicker::classname(), ['dateFormat' => 'yyyy-MM', 'options' => ['class' => 'form-control'], 'clientOptions' => ['changeMonth' => true, 'changeYear' => true]]) ?>
<?= $form->field($model, 'end_at')->widget(DatePicker::classname(), ['dateFormat' => 'yyyy-MM', 'options' => ['class' => 'form-control'], 'clientOptions' => ['changeMonth' => true, 'changeYear' => true]]) ?>
<?= $form->field($model, 'description')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end(); ?>