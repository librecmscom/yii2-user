<?php
/*
 * @var yii\web\View $this
 */
$this->title = Yii::t('user', 'Create Career');
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('user', 'Careers'),
    'url' => ['index']
];
$this->params['breadcrumbs'][] = $this->title;

?>
<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('/settings/_menu') ?>
    </div>
    <div class="col-md-9">
        <?= $this->render('_form', ['model' => $model]) ?>

    </div>
</div>