<?php

use yii\helpers\Html;
use yii\grid\GridView;

/*
 * @var yii\web\View $this
 */

$this->title = Yii::t('user', 'Educational experience');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Educational experience') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?php
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        'school',
                        'department',
                        'date',
                        'degree',
                        ['class' => 'yii\grid\ActionColumn',],
                    ],
                ]);
                ?>
                <div class="form-group">
                    <div class="edu-btn">
                        <?= Html::a(Yii::t('user', 'Create'), ['create'], ['class' => 'btn btn-primary btn-block']) ?>
                        <br>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>