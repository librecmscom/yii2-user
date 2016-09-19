<?php

/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Update Education: ') . ' ' . $model->school;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Educations'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('user', 'Update');
?>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <?= $this->render('/settings/_header') ?>
        <?= $this->render('_form', ['model' => $model]) ?>
    </div>
</div>