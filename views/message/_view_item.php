<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<?php
if (Yii::$app->user->id == $model->user_id) {//收件人是自己
    //标记已读
    $model->updateAttributes(['status' => 2]);
} ?>
<div class="media-left msg-d-left">
    <a href="<?= Url::to(['/user/profile/show', 'id' => $model->from_id]); ?>" rel="author">
        <img class="media-object" src="<?= $model->from->getAvatar('small'); ?>" alt="utf-8">
    </a>
</div>

<div class="media-body">
    <div class="media-heading p-m-username">
        <a class="p-m-u"
           href="<?= Url::to(['/user/profile/show', 'id' => $model->from_id]); ?>"><?= $model->from->username; ?></a>
        <span class="msg-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
    </div>
    <div class="media-content"><?= Html::encode($model->message); ?></div>
</div>