<?php
use yii\helpers\Url;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model yuncms\user\models\Withdrawals */

$this->title = Yii::t('user', 'Create Withdrawals');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Withdrawals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'Withdrawals') ?>
            <div class="pull-right">
                <a class="btn btn-primary"
                   href="<?= Url::to(['/user/withdrawals/index']); ?>"><?= Yii::t('user', 'Withdrawals'); ?></a>
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
