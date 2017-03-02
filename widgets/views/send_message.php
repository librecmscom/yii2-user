<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<?php $form = ActiveForm::begin([
    'id' => 'message-form',
    'action' => ['/user/message/send']
]); ?>
<?= $form->field($model, 'user_id')->label(false)->hiddenInput(); ?>
<?= $form->field($model, 'username')->label(false)->hiddenInput(); ?>
<?= $form->field($model, 'message')->label(false)->textarea(['class' => 'form-control']) ?>

<?= Html::submitButton(Yii::t('user', 'Send'), ['class' => 'btn btn-block btn-primary']) ?>

<?php ActiveForm::end(); ?>

