<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
//use xutl\inspinia\ActiveForm;

/* @var $this yii\web\View */
/* @var $model yuncms\user\backend\models\Settings */

$this->title = Yii::t('user', 'Settings');
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

            <?= $form->field($model, 'enableRegistration')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]); ?>
            <?= $form->field($model, 'enableRegistrationCaptcha')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]) ?>
            <?= $form->field($model, 'enableGeneratingPassword')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]) ?>
            <?= $form->field($model, 'enableConfirmation')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]) ?>
            <?= $form->field($model, 'enableUnconfirmedLogin')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]) ?>
            <?= $form->field($model, 'enablePasswordRecovery')->inline(true)
                ->radioList(['1' => Yii::t('yii', 'Yes'), '0' => Yii::t('yii', 'No')]) ?>

            <?= $form->field($model, 'rememberFor')->input('number') ?>
            <?= $form->field($model, 'confirmWithin')->input('number') ?>
            <?= $form->field($model, 'recoverWithin')->input('number') ?>
            <?= $form->field($model, 'cost')->input('number') ?>


            <?= Html::submitButton(Yii::t('user', 'Settings'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>