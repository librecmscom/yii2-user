<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;

$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;
?>
<div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= Nav::widget([
        'options' => ['class' => 'nav nav-tabs nav-main'],
        'items' => [
            ['label' => Yii::t('user', 'Profile Setting'), 'url' => ['/user/setting/profile']],
            ['label' => Yii::t('user', 'Avatar Setting'), 'url' => ['/user/setting/avatar']],
            ['label' => Yii::t('user', 'Privacy Setting'), 'url' => ['/user/setting/privacy'], 'visible' => false],
            ['label' => Yii::t('user', 'Account Setting'), 'url' => ['/user/setting/account']],
            ['label' => Yii::t('user', 'Social Network'), 'url' => ['/user/setting/networks'], 'visible' => $networksVisible],
        ]
    ]); ?>
</div>