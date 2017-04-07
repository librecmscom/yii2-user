<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel yuncms\user\models\BankCardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Bank Cards');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'Bank Cards') ?>
            <div class="pull-right">
                <a class="btn btn-primary" href="<?= Url::to(['/user/bankcard/create']); ?>" ><?= Yii::t('user', 'Create'); ?></a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?php Pjax::begin(); ?>    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        'bank',
                        'bank_city',
                        'bank_username',
                         'bank_name',
                         'bankcard_number',
                         'created_at:datetime',
                         'updated_at:datetime',
                        ['class' => 'yii\grid\ActionColumn'],
                    ],
                ]); ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
