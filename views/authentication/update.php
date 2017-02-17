<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use xutl\bootstrap\filestyle\FilestyleAsset;

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
                <?= $form->field($model, 'id_card') ?>
                <?= $form->field($model, 'imageFile')->fileInput(['class' => 'filestyle', 'data' => [
                    'buttonText' => Yii::t('app', 'Choose file')
                ]]); ?>
                <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                    'captchaAction' => '/user/authentication/captcha',

                ]); ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-success']) ?>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>
