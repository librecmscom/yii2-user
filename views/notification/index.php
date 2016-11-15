<?php
use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('user', 'Notice');
?>


<div class="row">
    <div class="col-md-3">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-9" style="margin-bottom:30px">

        <?= Html::a('全部标记为已读', ['/user/notification/read-all'], ['class' => 'btn btn-primary', 'data' => ['method' => 'post']]); ?>

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
