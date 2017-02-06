<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yuncms\user\models\Message;

if ($model->isRecipient()) {//收件人是自己 别人对你说
    $form = Html::a($model->from->username, ['/user/profile/show', 'id' => $model->from_id], ['rel' => 'author']);
    $to = Html::a(Yii::t('user', 'You'), ['/user/profile/show', 'id' => $model->user_id], ['rel' => 'author']);

} else {//你对别人说
    $form = Html::a(Yii::t('user', 'You'), ['/user/profile/show', 'id' => $model->from_id], ['rel' => 'author']);
    $to = Html::a($model->user->username, ['/user/profile/show', 'id' => $model->user_id], ['rel' => 'author']);
}
?>
<div class="media-left">
    <a href="<?= Url::to(['/user/profile/show', 'id' => $model->from_id]); ?>" rel="author">
        <img class="media-object" src="<?= $model->from->getAvatar('small'); ?>" alt="utf-8">
    </a>
</div>

<div class="media-body">
    <div class="media-heading">
        <?= Yii::t('user', '{form} say to {to}', ['form' => $form, 'to' => $to,]); ?>
    </div>
    <div class="media-content"><?= mb_substr(Html::encode($model->message), 0, 100) ?></div>
    <div class="media-action">
        <?= Yii::$app->formatter->asRelativeTime($model->created_at); ?>
    </div>
</div>