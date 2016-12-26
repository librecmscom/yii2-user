<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Show Education: ') . ' ' . $model->id;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Educations'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Show');
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 post-title"><?= Yii::t('user', 'Update Educational experience') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <p>
                    <?= Html::a(Yii::t('user', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a(Yii::t('user', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'school',
                        'department',
                        'degree',
                        'date',
                    ],
                ]) ?>

            </div>
        </div>
    </div>
</div>