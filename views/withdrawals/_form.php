<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\user\models\Withdrawals;

?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',

]); ?>

<?=$form->field($model, 'bankcard_id')->dropDownList(
    ArrayHelper::map(\yuncms\user\models\Bankcard::find()->select(['id', "CONCAT(bank,' - ',username,' - ',number) as name"])->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
);?>
<?=$form->field($model, 'money');?>

<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Yii::t('user', 'Create') : Yii::t('user', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
