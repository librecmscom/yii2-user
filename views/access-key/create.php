<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Create Access Key');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Access Keys'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-10">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => true,
        ]); ?>

        <?= $form->field($model, 'type') ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>