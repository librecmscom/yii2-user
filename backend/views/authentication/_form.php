<?php
use yii\helpers\Html;
use yuncms\user\models\Authentication;
use xutl\inspinia\ActiveForm;

/* @var \yii\web\View $this */
/* @var yuncms\user\models\Authentication $model */
/* @var ActiveForm $form */
?>
<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>


<?= $form->field($model, 'real_name')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_type')->dropDownList([
    Authentication::TYPE_ID => Yii::t('user', 'ID Card'),
    Authentication::TYPE_PASSPORT => Yii::t('user', 'Passport ID'),
    Authentication::TYPE_ARMYID => Yii::t('user', 'Army ID'),
    Authentication::TYPE_TAIWANID => Yii::t('user', 'Taiwan ID'),
    Authentication::TYPE_HKMCID => Yii::t('user', 'HKMC ID'),
]); ?>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'id_card')->textInput(['maxlength' => true]) ?>

<div class="hr-line-dashed"></div>

<?= $form->field($model, 'status')->inline(true)->radioList([
    0 => Yii::t('user', 'Pending review'),
    1 => Yii::t('user', 'Refuse'),
    2 => Yii::t('user', 'Passed'),
]) ?>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_cover">
    <label class="control-label col-sm-3"><?= Yii::t('user', 'Passport cover') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_cover); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_person_page">
    <label class="control-label col-sm-3"><?= Yii::t('user', 'Passport person page') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_person_page); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<div class="form-group field-authentication-passport_self_holding">
    <label class="control-label col-sm-3"><?= Yii::t('user', 'Passport self holding') ?></label>
    <div class="col-sm-6">
        <?= Html::img($model->passport_self_holding); ?>
    </div>
</div>
<div class="hr-line-dashed"></div>
<?= $form->field($model, 'failed_reason')->textInput(['maxlength' => true]) ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    </div>
</div>


<?php ActiveForm::end(); ?>

