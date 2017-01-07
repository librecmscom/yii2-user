<?php
use yuncms\system\widgets\ListGroup;

/** @var yuncms\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

$items = [
    [
        'label' => Yii::t('user', 'Profile Setting'),
        'url' => ['/user/setting/profile'],
        'icon' => 'glyphicon glyphicon-user',
    ],
    [
        'label' => Yii::t('user', 'Account Setting'),
        'url' => ['/user/setting/account'],
        'icon' => 'glyphicon glyphicon-cog'
    ],
    [
        'label' => Yii::t('user', 'Avatar Setting'),
        'url' => ['/user/setting/avatar'],
        'icon' => 'glyphicon glyphicon-picture'
    ],
    [
        'label' => Yii::t('user', 'Educations'),
        'url' => ['/user/education/index'],
        'icon' => 'fa fa-graduation-cap'
    ],
    [
        'label' => Yii::t('user', 'Wallet Manage'),
        'url' => ['/user/wallet/index'],
        'icon' => 'fa fa-money'
    ],
    [
        'label' => Yii::t('user', 'Coin Manage'),
        'url' => ['/user/coin/index'],
        'icon' => 'fa fa-gift'
    ],
    [
        'label' => Yii::t('user', 'Credit Manage'),
        'url' => ['/user/credit/index'],
        'icon' => 'fa fa-credit-card'
    ],
    [
        'label' => Yii::t('user', 'Careers'),
        'url' => ['/user/career/index'],
        'icon' => 'glyphicon glyphicon-list-alt'
    ],
    [
        'label' => Yii::t('user', 'Access Keys'),
        'url' => ['/user/access-key/index'],
        'icon' => 'glyphicon glyphicon-paperclip'
    ],
    [
        'label' => Yii::t('user', 'Social Networks'),
        'url' => ['/user/setting/networks'],
        'icon' => 'glyphicon glyphicon-retweet',
        'visible' => $networksVisible
    ],
    [
        'label' => Yii::t('user', 'Notification alerts'),
        'url' => ['/user/notification/index'],
        'icon' => 'glyphicon glyphicon-bell'
    ]
];
?>

<?= ListGroup::widget([
    'options' => [
        'class' => 'list-group',
    ],
    'encodeLabels' => false,
    'itemOptions' => [
        'class' => 'list-group-item'
    ],
    'items' => $items
]) ?>
