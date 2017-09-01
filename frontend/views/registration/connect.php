<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\models\User $model
 * @var yuncms\user\models\Social $account
 */

$this->title = Yii::t('user', 'Sign in');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-md-offset-3">
    <h1 class="h4 text-center text-muted"><?= Html::encode($this->title) ?></h1>
    <div class="alert alert-info">
        <p>
            <?= Yii::t('user', 'In order to finish your registration, we need you to enter following fields') ?>
            :
        </p>
    </div>
    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <?= $form->field($model, 'email', ['inputOptions' => ['autocomplete' => 'off', 'required' => true, 'type' => 'email']]) ?>

    <?= $form->field($model, 'username', ['inputOptions' => ['autocomplete' => 'off', 'required' => true]]) ?>

    <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-success btn-block']) ?>

    <?php ActiveForm::end(); ?>
    <p class="text-center">
        <?= Html::a(Yii::t('user', 'If you already registered, sign in and connect this account on settings page'), ['/user/settings/networks']) ?>
        .
    </p>
</div>
