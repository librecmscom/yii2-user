<?php

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Update Educational experience');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Educational experience'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Update');
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 post-title"><?= Yii::t('user', 'Update Educational experience') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>