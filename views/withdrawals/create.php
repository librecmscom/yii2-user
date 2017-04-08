<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\user\models\Withdrawals;

/* @var $this yii\web\View */
/* @var $model yuncms\user\models\BankCard */

$this->title = Yii::t('user', 'Withdrawals');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Html::encode($this->title) ?>
            <div class="pull-right">
                <a class="btn btn-primary" href="<?= Url::to(['/user/wallet/index']); ?>"
                ><?= Yii::t('user', 'Wallet'); ?></a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?php $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                ]); ?>

                <div class="form-group field-withdrawals-money required">
                    <label class="control-label col-sm-3" for="withdrawals-money">币种</label>
                    <div class="col-sm-6">
                        <?= $wallet->money ?>
                    </div>
                </div>

                <div class="form-group field-withdrawals-money required">
                    <label class="control-label col-sm-3" for="withdrawals-money">币种</label>
                    <div class="col-sm-6">
                        <?= $currency ?>
                    </div>
                </div>

                <?= $form->field($model, 'bankcard_id')->dropDownList(
                    ArrayHelper::map(\yuncms\user\models\Bankcard::find()->select(['id', "CONCAT(bank,' - ',username,' - ',number) as name"])->where(['user_id' => Yii::$app->user->id])->asArray()->all(), 'id', 'name')
                ); ?>
                <?= $form->field($model, 'money'); ?>

                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user', 'Create'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>