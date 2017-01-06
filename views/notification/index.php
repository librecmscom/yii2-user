<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Notice');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'Notice') ?>
            <div class="pull-right">
                <a class="btn btn-primary" href="<?=Url::to(['/user/notification/read-all'])?>" data-method="post"><?=Yii::t('user', 'All marked as read')?></a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['tag' => 'li', 'class' => 'media'],
                    'itemView' => '_item',//子视图
                    'layout' => "{items}\n{pager}",
                    'options' => [
                        'tag' => 'ul',
                        'class' => 'media-list'
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
