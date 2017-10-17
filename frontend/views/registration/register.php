<?php

use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use yuncms\user\frontend\widgets\Connect;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 * @var yuncms\user\frontend\models\RegistrationForm $model
 * @var yuncms\user\Module $module
 * @var boolean $enableGeneratingPassword
 * @var boolean $enableRegistrationCaptcha
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

    <?= $form->field($model, 'nickname', ['inputOptions' => ['autocomplete' => 'off', 'required' => true]]) ?>

    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off', 'required' => true, 'type' => 'email']]) ?>

    <?php if ($enableGeneratingPassword == false): ?>
        <?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'off', 'required' => true]])->passwordInput() ?>
    <?php endif ?>

    <?php if ($enableRegistrationCaptcha == true): ?>
        <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                'captchaAction' => '/user/registration/captcha',
                'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-3">{image}</div></div>']
        ); ?>
    <?php endif ?>

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
