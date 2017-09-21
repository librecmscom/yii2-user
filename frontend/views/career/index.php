<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var yii\web\View $this */
/* @var yuncms\user\models\Career $model */
/* @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('user', 'Careers');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'Careers') ?>
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
                        'name',
                        'position',
                        'city',
                        'start_at',
                        'end_at',
                        ['class' => 'yii\grid\ActionColumn',],
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
