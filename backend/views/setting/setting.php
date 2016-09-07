<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

use backend\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\Jarvis;

$this->title = Yii::t('user', 'User Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php Jarvis::begin([
                'editbutton' => false,
                'deletebutton' => false,
                'header' => Html::encode($this->title),
            ]); ?>
            <?php $form = ActiveForm::begin(['layout' => 'horizontal',]); ?>
            <fieldset>
                <?= $form->field($model, 'enableRegistration')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
                <?= $form->field($model, 'enableRegistrationCaptcha')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
                <?= $form->field($model, 'enableGeneratingPassword')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
                <?= $form->field($model, 'enableConfirmation')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
                <?= $form->field($model, 'enableUnconfirmedLogin')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]); ?>
                <?= $form->field($model, 'enablePasswordRecovery')->inline(true)->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')]) ?>
            </fieldset>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>