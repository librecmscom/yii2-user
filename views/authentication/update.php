<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use xutl\bootstrap\filestyle\FilestyleAsset;
use yuncms\user\models\Authentication;

FilestyleAsset::register($this);
/*
 * @var yii\web\View $this
 * @var yuncms\user\models\Authentication $model
 */

$this->title = Yii::t('user', 'Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Authentication') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>

                <?= $form->field($model, 'real_name') ?>
                <?= $form->field($model, 'id_type')->dropDownList([
                    Authentication::TYPE_ID => Yii::t('user', 'ID Card'),
                    Authentication::TYPE_PASSPORT => Yii::t('user', 'Passport ID'),
                    Authentication::TYPE_ARMYID => Yii::t('user', 'Army ID'),
                    Authentication::TYPE_TAIWANID => Yii::t('user', 'Taiwan ID'),
                    Authentication::TYPE_HKMCID => Yii::t('user', 'HKMC ID'),
                ]); ?>
                <?= $form->field($model, 'id_card') ?>
                <?= $form->field($model, 'id_file')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('user', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'id_file1')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('user', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'id_file2')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('user', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'captchaAction' => '/user/authentication/captcha',

                ]); ?>

                <?= $form->field($model, 'registrationPolicy')->checkbox()->label(
                    Yii::t('user', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
                        'serviceAgreement' => Html::a(Yii::t('user', 'Service Agreement'), ['/legal/terms']),
                        'privacyPolicy' => Html::a(Yii::t('user', 'Privacy Policy'), ['/legal/privacy']),
                    ]), [
                        'encode' => false
                    ]
                ) ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
