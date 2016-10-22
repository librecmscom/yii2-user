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
            ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
            ['label' => Yii::t('user', 'Portrait'), 'url' => ['/user/settings/portrait']],
            ['label' => Yii::t('user', 'Privacy settings'), 'url' => ['/user/settings/privacy'], 'visible' => false],
            ['label' => Yii::t('user', 'Account'), 'url' => ['/user/settings/account']],
            ['label' => Yii::t('user', 'Social network'), 'url' => ['/user/settings/networks'], 'visible' => $networksVisible],
        ]
    ]); ?>
</div>