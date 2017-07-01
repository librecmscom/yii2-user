<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yuncms\user\models\Rest;
/*
 * @var yii\web\View $this
 */

$this->title = Yii::t('user', 'Access Keys');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'Access Keys') ?>
            <div class="pull-right">
                <?= Html::a(Yii::t('user', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        'id',
                        'token',
                        'rate_limit',
                        'rate_period',
                        [
                            'header' => Yii::t('user', 'Status'),
                            'value' => function ($model) {
                                if ($model->status == Rest::STATUS_BLOCK) {
                                    return Html::tag('span', Yii::t('user', 'Blockade'), ['class' => 'label label-danger']);
                                } else {
                                    return Html::tag('span', Yii::t('user', 'Normal'), ['class' => 'label label-success']);
                                }
                            },
                            'format' => 'raw',
                        ],
                        'created_at:datetime',
                        ['class' => 'yii\grid\ActionColumn', 'template' => '{delete}'],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
