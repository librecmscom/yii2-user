<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yuncms\user\models\Message;

/** @var \yuncms\user\models\Message $model */
/** @var \yuncms\user\models\Message $message */
$message = $model->lastMessage;

if ($message->isRecipient()) {//收件人是自己 别人对你说
    $form = Html::a($message->from->username, ['/user/profile/show', 'id' => $message->from_id], ['rel' => 'author']);
    $to = Html::a(Yii::t('user', 'You'), ['/user/profile/show', 'id' => $message->user_id], ['rel' => 'author']);
} else {//你对别人说
    $form = Html::a(Yii::t('user', 'You'), ['/user/profile/show', 'id' => $message->from_id], ['rel' => 'author']);
    $to = Html::a($message->user->username, ['/user/profile/show', 'id' => $message->user_id], ['rel' => 'author']);
}
?>

<div class="media-left">
    <a href="<?= Url::to(['/user/profile/show', 'id' => $message->from_id]); ?>" rel="author">
        <img class="media-object" src="<?= $message->from->getAvatar('small'); ?>" alt="utf-8">
    </a>
</div>

<div class="media-body">
    <div class="media-heading">
        <?= Yii::t('user', '{form} say to {to}', ['form' => $form, 'to' => $to,]); ?>
    </div>
    <div class="media-content"><?= mb_substr(Html::encode($message->message), 0, 100) ?></div>
    <div class="media-action"><?= Yii::$app->formatter->asRelativeTime($message->created_at); ?>
        <span class="pull-right">
                    <?= $message->status == Message::STATUS_NEW ? Yii::t('user', 'Unread') : Yii::t('user', 'Read'); ?>
            |
                    <a href="<?= Url::to(['/user/message/view', 'id' => $message->parent ? $message->parent : $message->id]); ?>"><?= Yii::t('user', 'Detail'); ?>
                        (<?= $model->getMessages()->count() ?>)</a>
                </span>
    </div>
</div>
