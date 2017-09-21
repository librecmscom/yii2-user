<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\frontend\models\SettingsForm $model
 */
$this->title = Yii::t('user', 'Security Setting');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Security Setting') ?></h2>
        <div class="row">
            <div class="col-md-8">
                <?php $form = ActiveForm::begin([
                    'id' => 'account-form',
                    'options' => ['class' => 'form-horizontal'],
                    'fieldConfig' => [
                        'template' => "{label}\n<div class=\"col-sm-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-sm-9\">{error}\n{hint}</div>",
                        'labelOptions' => ['class' => 'col-sm-3 control-label'],
                    ],
                    'enableAjaxValidation' => true,
                    'enableClientValidation' => false,
                ]); ?>

                <?= $form->field($model, 'nickname') ?>

                <?= $form->field($model, 'username', [
                    'inputTemplate' => '<div class="input-group"><span class="input-group-addon">' . mb_substr(Url::to(['/user/space/show', 'username' => $model->username], true), 0, -mb_strlen($model->username)) . '</span>{input}</div>',
                ])->label(Yii::t('user', 'Personality URL')) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'new_password')->passwordInput() ?>

                <?= $form->field($model, 'current_password')->passwordInput() ?>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-success']) ?><br>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>


            </div>
        </div>
    </div>
</div>