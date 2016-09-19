<?php
use yii\helpers\Html;
use Leaps\Captcha\Captcha;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 * @var yuncms\user\Module $module
 */

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="panel-body">
                <?php $form = ActiveForm::begin([
                    'id' => 'registration-form',
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'username') ?>

                <?php if ($module->enableGeneratingPassword == false): ?>
                    <?= $form->field($model, 'password')->passwordInput() ?>
                <?php endif ?>

                <?php if ($module->enableRegistrationCaptcha == true): ?>
                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                            'captchaAction' => '/user/registration/captcha',
                            'template' => '<div class="row"><div class="col-lg-6">{input}</div><div class="col-lg-3">{image}</div></div>']
                    ); ?>
                <?php endif ?>

                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-success btn-block']) ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <p class="text-center">
            <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
        </p>
    </div>
</div>
