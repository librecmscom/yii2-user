<?php
use yii\widgets\Menu;

/** @var yuncms\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <?= $user->username ?>
        </h3>
    </div>
    <div class="panel-body">
        <?= Menu::widget([
            'options' => [
                'class' => 'nav nav-pills nav-stacked',
            ],
            'items' => [
                ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/setting/profile']],
                ['label' => Yii::t('user', 'Account'), 'url' => ['/user/setting/account']],
                ['label' => Yii::t('user', 'Educations'), 'url' => ['/user/education/index']],
                ['label' => Yii::t('user', 'Careers'), 'url' => ['/user/career/index']],
                ['label' => Yii::t('user', 'Access Keys'), 'url' => ['/user/access-key/index']],
                ['label' => Yii::t('user', 'Social Networks'), 'url' => ['/user/setting/networks'], 'visible' => $networksVisible],
            ],
        ]) ?>
    </div>
</div>
