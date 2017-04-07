<?php
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yuncms\user\models\BankCard */

$this->title = Yii::t('user', 'Create Bank Card');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Bank Cards'), 'url' => ['index']];
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
                <a class="btn btn-primary" href="<?= Url::to(['/user/bankcard/index']); ?>"
                   ><?= Yii::t('user', 'Bank Cards'); ?></a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', [
                    'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>
