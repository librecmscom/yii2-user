<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model yuncms\user\models\Withdrawals */

$this->title = $model->id;
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
                <a class="btn btn-primary"
                   href="<?= Url::to(['/user/wallet/index']); ?>"><?= Yii::t('user', 'Create'); ?></a>
                <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('user', 'Are you sure you want to delete this item?'),
                        'method' => 'post',
                    ],
                ]) ?>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">


                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'bankcard_id',
                        'currency',
                        'money',
                        'status',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>
