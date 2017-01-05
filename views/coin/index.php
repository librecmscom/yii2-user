<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Modal;

/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 */
$this->title = Yii::t('user', 'Coin Manage');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="row">
        <div class="col-md-2">
            <?= $this->render('/setting/_menu') ?>
        </div>
        <div class="col-md-10">
            <h2 class="h3 profile-title"><?= Yii::t('user', 'Coin Manage') ?></h2>
            <div class="row">
                <div class="col-md-8">
                    <p class="mb-20">
                        您当前的金币数为：<strong class="text-gold"><?= Yii::$app->user->identity->userData->coins ?></strong>
                        <span class="ml-10">[ <a href="#" data-toggle="modal" data-target="#charge_modal">立即充值</a> ]</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

<?php $form = ActiveForm::begin([
    'action' => \yii\helpers\Url::toRoute(['recharge']),
    'fieldConfig' => [
        'labelOptions' => ['class' => 'control-label'],
    ],
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
]); ?>
    <!-- Modal
    ================================================== -->
<?php Modal::begin(
    [
        'options' => ['id' => 'charge_modal'],
        'header' => '金币充值',
        'footer' => Html::button(Yii::t('app', 'Clean'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']),
    ]
); ?>
    这里其实是个表单
<?php Modal::end(); ?>
<?php ActiveForm::end(); ?>