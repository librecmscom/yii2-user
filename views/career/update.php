<?php

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Update Career: ') . ' ' . $model->name;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Careers'),
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
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Update Career: ') . ' ' . $model->name ?></h2>
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_form', ['model' => $model]) ?>
            </div>
        </div>
    </div>
</div>