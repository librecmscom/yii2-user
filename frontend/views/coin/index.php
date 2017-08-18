<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\payment\models\Payment;
use yuncms\user\models\Recharge;

/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 */
$this->title = Yii::t('user', 'Coin Manage');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Coin Manage') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <p class="mb-20">
                    <?= Yii::t('user', 'Your current coins are:') ?><strong
                        class="text-gold"><?= Yii::$app->user->identity->extend->coins ?></strong>
                    <?php
                    if (Yii::$app->hasModule('payment')):
                        ?>
                        <span class="ml-10">[ <a href="#" data-toggle="modal"
                                                 data-target="#charge_modal"><?= Yii::t('user', 'Recharge') ?></a> ]</span>
                    <?php endif; ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        'id',
                        'action',
                        'coins',
                        'source_subject',
                        'created_at:datetime'
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
<?php
if (Yii::$app->hasModule('payment')):
    $recharge = new Recharge();
    $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::toRoute(['/user/coin/recharge']),
    ]); ?>
    <?= Html::activeInput('hidden', $recharge, 'currency', ['value' => Yii::$app->language == 'zh-CN' ? 'CNY' : 'USD']) ?>
    <?= Html::activeInput('hidden', $recharge, 'trade_type', ['value' => Payment::TYPE_NATIVE]) ?>
    <!-- Modal
    ================================================== -->
    <?php Modal::begin([
    'options' => ['id' => 'charge_modal'],
    'header' => Yii::t('user', 'Coin Recharge'),
    'footer' => Html::button(Yii::t('user', 'Clean'), ['class' => 'btn btn-default', 'data-dismiss' => 'modal']) . Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-primary']),
]); ?>
    <?= $form->field($recharge, 'money')->hint('1元一个金币'); ?>
    <?= $form->field($recharge, 'gateway')->inline(true)->radioList(ArrayHelper::map(Yii::$app->getModule('payment')->gateways, 'id', 'title')); ?>
    <?php
    Modal::end();
    ActiveForm::end();
endif;
?>
