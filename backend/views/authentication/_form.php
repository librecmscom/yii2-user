<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var \yii\web\View $this */
/* @var yuncms\user\models\Authentication $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
<fieldset>
    <?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_card')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->inline(true)->radioList([
        0 => Yii::t('user', 'Pending review'),
        1 => Yii::t('user', 'Refuse'),
        2 => Yii::t('user', 'Passed'),
    ]) ?>

    <?= $form->field($model, 'failed_reason')->textInput(['maxlength' => true]) ?>


</fieldset>
<div class="form-actions">
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

