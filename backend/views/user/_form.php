<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;

/**
 * @var yii\bootstrap\ActiveForm $form
 * @var yuncms\user\models\User $model
 */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

<?= $form->field($model, 'nickname')->textInput(['maxlength' => 255]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'mobile')->textInput(['maxlength' => 255]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'password')->passwordInput() ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
