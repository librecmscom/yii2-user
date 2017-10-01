<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\frontend\models\PrivacyForm $model
 */
$this->title = Yii::t('user', 'Privacy Setting');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Privacy Setting') ?></h2>
        <div class="row">
            <div class="col-md-8">
                <?php $form = ActiveForm::begin([
                    'id' => 'privacy-form',
                    'options' => ['class' => 'form-horizontal'],
                ]); ?>


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
