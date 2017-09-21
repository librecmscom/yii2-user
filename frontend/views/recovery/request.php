<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var yii\web\View $this */
/* @var yuncms\user\frontend\models\RecoveryForm $model */
/* @var yii\widgets\ActiveForm $form */

$this->title = Yii::t('user', 'Recover your password');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="col-md-6 col-md-offset-3">
    <h1 class="h4 text-center text-muted"><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin([
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

    <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-primary btn-block']) ?><br>

    <?php ActiveForm::end(); ?>
</div>