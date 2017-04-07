<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\payment\models\Payment;
use yuncms\user\models\Withdrawals;
/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model yuncms\user\models\SettingsForm
 */
$this->title = Yii::t('user', 'Wallet Manage');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="row">
        <div class="col-md-2">
            <?= $this->render('/_profile_menu') ?>
        </div>
        <div class="col-md-10">
            <h2 class="h3 profile-title"><?= Yii::t('user', 'Wallet') ?></h2>
            <div class="row">
                <div class="col-md-12">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'layout' => "{items}\n{pager}",
                        'columns' => [
                            'currency',
                            'money',
                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => Yii::t('user', 'Operation'),
                                'template' => '{recharge}',
                                'buttons' => [
                                    'recharge' =>
                                        function ($url, $model, $key) {
                                            return '<a href="#" onclick="jQuery(\'#payment-currency\').val(\'' . $model->currency . '\');" data-toggle="modal"
                                                 data-target="#recharge_modal">' . Yii::t('user', 'Recharge') . '</a>   <a href="#" onclick="jQuery(\'#withdrawals-currency\').val(\'' . $model->currency . '\');" data-toggle="modal"
                                                 data-target="#withdrawals_modal">' . Yii::t('user', 'Withdrawals') . '</a>';
                                        }]],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal
    ================================================== -->
<?php
if (Yii::$app->hasModule('payment')):
    $payment = new Payment();
    $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::toRoute(['/payment/default/index']),
    ]); ?>
    <?= Html::activeInput('hidden', $payment, 'currency', ['value' => '']) ?>
    <?= Html::activeInput('hidden', $payment, 'pay_type', ['value' => Payment::TYPE_RECHARGE]) ?>
    <?php Modal::begin([
    'options' => ['id' => 'recharge_modal'],
    'header' => Yii::t('user', 'Recharge'),
    'footer' => Html::button(Yii::t('user', 'Clean'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']),
]); ?>
    <?= $form->field($payment, 'money'); ?>
    <?= $form->field($payment, 'gateway')->inline(true)->radioList(ArrayHelper::map(Yii::$app->getModule('payment')->gateways, 'id', 'title')); ?>
    <?php
    Modal::end();
    ActiveForm::end();
endif;
$withdrawals = new Withdrawals();
$form = ActiveForm::begin([
    'action' => \yii\helpers\Url::toRoute(['/user/withdrawals/create']),
    'enableAjaxValidation' => true,
]);
echo Html::activeInput('hidden', $withdrawals, 'currency', ['value' => '']);
Modal::begin([
    'options' => ['id' => 'withdrawals_modal'],
    'header' => Yii::t('user', 'Withdrawals'),
    'footer' => Html::button(Yii::t('user', 'Clean'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']),
]);

echo $form->field($withdrawals, 'bankcard_id')->dropDownList(
    ArrayHelper::map(\yuncms\user\models\Bankcard::find()->select(['id', "CONCAT(bank,' - ',username,' - ',number) as name"])->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
);
echo $form->field($withdrawals, 'money');
Modal::end();
ActiveForm::end();
?>