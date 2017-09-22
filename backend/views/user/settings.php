<?php

use yii\helpers\Html;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use xutl\inspinia\ActiveForm;
use yuncms\user\models\Settings;

/* @var $this yii\web\View */
/* @var $model yuncms\user\models\Settings */

$this->title = Yii::t('user', 'Settings');
$this->params['breadcrumbs'][] = Yii::t('user', 'Manage Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12 authentication-update">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget([
                        'items' => [
                            [
                                'label' => Yii::t('user', 'Manage User'),
                                'url' => ['/user/user/index'],
                            ],
                            [
                                'label' => Yii::t('user', 'Create User'),
                                'url' => ['/user/user/create'],
                            ],
                            [
                                'label' => Yii::t('user', 'Settings'),
                                'url' => ['/user/user/settings'],
                            ],
                        ]
                    ]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal'
            ]); ?>

            <?= $form->field($model, 'enableRegistration')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableMobileRegistration')->inline()->checkbox([], false); ?>
            <?= $form->field($model, 'enableRegistrationCaptcha')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableGeneratingPassword')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableConfirmation')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enableUnconfirmedLogin')->inline()->checkbox([], false) ?>
            <?= $form->field($model, 'enablePasswordRecovery')->inline()->checkbox([], false) ?>

            <?= $form->field($model, 'emailChangeStrategy')->inline()->dropDownList([
                Settings::STRATEGY_INSECURE => Yii::t('user', 'Insecure'),
                Settings::STRATEGY_DEFAULT => Yii::t('user', 'Default'),
                Settings::STRATEGY_SECURE => Yii::t('user', 'Secure'),
            ], [
                'prompt' => Yii::t('user', 'Please select')
            ]) ?>
            <?= $form->field($model, 'mobileChangeStrategy')->inline()->dropDownList([
                Settings::STRATEGY_INSECURE => Yii::t('user', 'Insecure'),
                Settings::STRATEGY_DEFAULT => Yii::t('user', 'Default'),
                Settings::STRATEGY_SECURE => Yii::t('user', 'Secure'),
            ], [
                'prompt' => Yii::t('user', 'Please select')
            ]) ?>

            <?= $form->field($model, 'avatarPath') ?>
            <?= $form->field($model, 'avatarUrl') ?>


            <?= $form->field($model, 'rememberFor', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('user', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('user', 'The time you want the user will be remembered without asking for credentials.')) ?>
            <?= $form->field($model, 'confirmWithin', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('user', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('user', 'The time before a confirmation token becomes invalid.')) ?>
            <?= $form->field($model, 'recoverWithin', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('user', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('user', 'The time before a recovery token becomes invalid.')) ?>
            <?= $form->field($model, 'cost', [
                'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">' . Yii::t('user', 'Second') . '</span></div>',
            ])->input('number')->hint(Yii::t('user', 'Cost parameter used by the Blowfish hash algorithm.')) ?>

            <?= Html::submitButton(Yii::t('user', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>