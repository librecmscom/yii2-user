<?php
use yuncms\system\widgets\ListGroup;

/** @var yuncms\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

$items = [
    [
        'label' => Yii::t('user', 'My Message'),
        'url' => ['/user/message/index'],
    ],
    [
        'label' => Yii::t('user', 'Notifications'),
        'url' => ['/user/notification/index'],
    ]

];
?>

<?= ListGroup::widget([
    'options' => [
        'tag' => 'div',
        'class' => 'widget-messages mt-30',
    ],
    'encodeLabels' => false,
    'itemOptions' => [
        'class' => 'widget-message-item '
    ],
    'items' => $items
]) ?>
