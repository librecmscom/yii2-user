<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\system\helpers\DateHelper;
use yuncms\system\helpers\CountryHelper;

/*
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var yuncms\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Profile settings') ?></h2>
        <div class="row">
            <div class="col-md-8">

                <?php $form = ActiveForm::begin([
                    'id' => 'profile-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                    'validateOnBlur' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'gender')->inline(true)->radioList(['0' => Yii::t('user', 'Secrecy'), '1' => Yii::t('user', 'Male'), '2' => Yii::t('user', 'Female')], [
                    'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-9\">{error}\n{hint}</div>",
                ]); ?>

                <?= $form->field($model, 'country')->dropDownList(ArrayHelper::map(CountryHelper::getCountryAll(), 'identifier', 'name'), [
                    'prompt' => Yii::t('user', 'Please select')
                ]); ?>

                <?= $form->field($model, 'location') ?>

                <?= $form->field($model, 'address') ?>

                <?= $form->field($model, 'website') ?>

                <?= $form->field($model, 'timezone')->dropDownList(ArrayHelper::map(DateHelper::getTimeZoneAll(), 'identifier', 'name'), [
                    'prompt' => Yii::t('user', 'Please select')
                ]); ?>

                <?= $form->field($model, 'introduction')->textarea() ?>

                <?= $form->field($model, 'bio')->textarea() ?>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success']) ?>
                        <br>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>