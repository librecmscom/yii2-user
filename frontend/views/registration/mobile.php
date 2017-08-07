<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yuncms\user\widgets\Connect;
use xutl\sms\captcha\Captcha;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 * @var yuncms\user\Module $module
 */

$this->title = Yii::t('user', 'Sign up');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-md-offset-3">
    <h1 class="h4 text-center text-muted"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
    ]); ?>

    <?= $form->field($model, 'mobile')->input('number', [
        'placeholder' => Yii::t('user','please fill in cell phone number.'),
        'maxlength' => '11',
    ]) ?>

    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
            'mobileField' => 'newMobile',
            'captchaAction' => '/user/registration/sms-captcha',
            'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-3">{button}</div></div>']
    ); ?>

    <?= $form->field($model, 'registrationPolicy')->checkbox()->label(
        Yii::t('user', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
            'serviceAgreement' => Html::a(Yii::t('user', 'Service Agreement'), ['/legal/terms']),
            'privacyPolicy' => Html::a(Yii::t('user', 'Privacy Policy'), ['/legal/privacy']),
        ]), [
            'encode' => false
        ]
    ) ?>

    <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block btn-lg']) ?>

    <?php ActiveForm::end(); ?>
    <hr>
    <div class="widget-login pt-30">
        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
        <?= Connect::widget([
            'baseAuthUrl' => ['/user/security/auth'],
        ]) ?>
    </div>
</div>
