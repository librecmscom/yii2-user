<?php

use yii\grid\GridView;

/**
 * @var yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 */
?>
<?php $this->beginContent('@yuncms/user/backend/views/user/update.php', ['model' => $model]) ?>

<?php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{pager}",
    'columns' => [
        'school',
        'department',
        'date',
        'degree'
    ],
]);
?>
<?php $this->endContent() ?>
