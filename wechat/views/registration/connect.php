<?php

use yii\helpers\Html;
use xutl\weui\ActiveForm;
use yii\bootstrap\BootstrapAsset;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\models\User $model
 * @var yuncms\user\models\Social $account
 */
BootstrapAsset::register($this);
$this->title = Yii::t('user', 'Sign in');
//$this->params['breadcrumbs'][] = $this->title;
?>

<div class="weui-cells weui-cells_form">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">qq</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="number" pattern="[0-9]*" placeholder="请输入qq号">
        </div>
    </div>

    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="" class="weui-label">日期</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="date" value="">
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="" class="weui-label">时间</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="datetime-local" value="" placeholder="">
        </div>
    </div>
    <div class="weui-cell weui-cell_vcode">
        <div class="weui-cell__hd"><label class="weui-label">验证码</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" type="number" placeholder="请输入验证码">
        </div>
        <div class="weui-cell__ft">
            <img class="weui-vcode-img" src="./images/vcode.jpg">
        </div>
    </div>
</div>


<?php $form = ActiveForm::begin(); ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'username') ?>
<?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-success btn-block']) ?>

<?php ActiveForm::end(); ?>
