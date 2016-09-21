<?php

use yii\grid\GridView;

/**
 * @var yii\web\View
 * @var yii\data\ActiveDataProvider $dataProvider
 */
?>
<?php $this->beginContent('@vendor/yuncms/yii2-user/backend/views/user/update.php', ['user' => $user]) ?>

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
