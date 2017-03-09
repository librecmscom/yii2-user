<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yuncms\user\models\User;

/**
 * @var \yii\web\View $this
 * @var  User $model
 * @var string $type
 */
$this->context->layout = 'space';
$this->params['user'] = $model;

if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {//Me
    $this->title = Yii::t('user', '{who} collected {what}', [
        'who' => Yii::t('user', 'My'),
        'what' => Yii::t('user', 'articles'),
    ]);
} else {
    $this->title = Yii::t('user', '{who} collected {what}', [
        'who' => empty($model->profile->name) ? Html::encode($model->username) : Html::encode($model->profile->name),
        'what' => 'articles'
    ]);
}
?>
<div class="stream-following">

    <?= Nav::widget([
        'options' => ['class' => 'nav nav-tabs'],
        'items' => [
            //问答
            ['label' => Yii::t('user', 'Collected {what}', ['what' => Yii::t('user', 'Questions')]), 'url' => ['/user/profile/collected', 'id' => $model->id, 'type' => 'questions'], 'visible' => Yii::$app->hasModule('question')],
            //文章
            ['label' => Yii::t('user', 'Collected {what}', ['what' => Yii::t('user', 'Articles')]), 'url' => ['/user/profile/collected', 'id' => $model->id, 'type' => 'articles'], 'visible' => Yii::$app->hasModule('article')],
        ],
    ]); ?>

    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => 'section', 'class' => 'stream-list-item'],
        'itemView' => '_collected',//子视图
        'viewParams' => ['type' => $type],
        'layout' => "{items}\n{pager}",
        'options' => [
            'tag' => 'div',
            'class' => 'stream-list question-stream mt-10'
        ]
    ]); ?>
</div>





