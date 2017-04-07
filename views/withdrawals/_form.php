<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\user\models\Bankcard;


/* @var $this yii\web\View */
/* @var $model yuncms\user\models\Withdrawals */
/* @var $form yii\widgets\ActiveForm */
?>


<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',

]); ?>

<?= $form->field($model, 'bankcard_id')->dropDownList(
    ArrayHelper::map(Bankcard::find()->select(['id', "CONCAT(bank,' - ',bank_username,' - ',bankcard_number) as name"])->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
) ?>

<?= $form->field($model, 'money') ?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>


