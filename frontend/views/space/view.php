<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\user\models\User;

/**
 * Profile 首页
 * @var \yii\web\View $this
 * @var  User $model
 */
if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {//Me
    $who = Yii::t('user', 'My');
} else {
    $who = Html::encode($model->username);
}
$this->context->layout = 'space';
$this->title = Yii::t('user', '{who} Page', [
    'who' => $who,
]);
$this->params['user'] = $model;
?>
<h2 class="h4"><?= Yii::t('user', 'Latest Activities') ?></h2>
<div class="stream-doing clearfix">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemOptions' => ['tag' => 'li', 'class' => 'media'],
        'itemView' => '_item',//子视图
        'layout' => "{items}\n{pager}",
        'options' => [
            'tag' => 'ul',
            'class' => 'media-list'
        ]
    ]); ?>
</div>
