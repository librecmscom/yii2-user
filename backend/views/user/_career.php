<?php
use yii\grid\GridView;
/**
 * @var yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 */
?>
<?php $this->beginContent('@vendor/yuncms/yii2-user/backend/views/user/update.php', ['model' => $model]) ?>

<?php
echo GridView::widget([
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
