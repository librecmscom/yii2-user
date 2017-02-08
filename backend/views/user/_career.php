<?php
use yii\grid\GridView;
/**
 * @var yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 */
$this->params['noPadding'] = true;
?>
<?php $this->beginContent('@yuncms/user/backend/views/user/update.php', ['model' => $model]) ?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'layout'=>"{items}\n{pager}",
    'columns' => [
        'name',
        'position',
        'city',
        'start_at',
        'end_at',
    ],
]);
?>
<?php $this->endContent() ?>
