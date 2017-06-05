<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('user', 'People');
?>

<?php
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_item',//子视图

    //'layout' => "{sorter}\n{items}\n{pager}",
    'layout' => "{items}\n{pager}",
//    'viewParams' => [
//        'params' => $params,
//    ]
]);
?>