<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yuncms\payment\models\Payment;

/** @var \yuncms\payment\GatewayInterface[] $gateways */
$gateways = Yii::$app->getModule('payment')->gateways;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('@yuncms/user/views/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Coin Recharge') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal'
                ]); ?>
                <?= Html::activeInput('hidden', $model, 'currency', ['value' => Yii::$app->language == 'zh-CN' ? 'CNY' : 'USD']) ?>
                <?= Html::activeInput('hidden', $model, 'trade_type', ['value' => Payment::TYPE_NATIVE]) ?>

                <?= $form->field($model, 'money'); ?>
                <?= $form->field($model, 'gateway')->inline(true)->radioList(ArrayHelper::map($gateways, 'id', 'title')); ?>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <?= Html::submitButton(Yii::t('user', 'Payment'), ['class' => 'btn btn-success']) ?>
                        <br>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>

            </div>
        </div>
    </div>
</div>