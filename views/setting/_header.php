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
            ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/setting/profile']],
            ['label' => Yii::t('user', 'Avatar'), 'url' => ['/user/setting/avatar']],
            ['label' => Yii::t('user', 'Privacy settings'), 'url' => ['/user/setting/privacy'], 'visible' => false],
            ['label' => Yii::t('user', 'Account'), 'url' => ['/user/setting/account']],
            ['label' => Yii::t('user', 'Social network'), 'url' => ['/user/setting/networks'], 'visible' => $networksVisible],
        ]
    ]); ?>
</div>