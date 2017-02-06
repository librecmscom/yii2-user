<?php
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php
if (Yii::$app->user->getId() == $model->user_id) {//收件人是自己
    ?>
    <a class="msg-detail" href="<?= Url::to(['/user/message/view', 'id' => $model->id]); ?>"></a>
    <div class="media-left msg-d-left">
        <a href="<?= Url::to(['/user/profile/show', 'id' => $model->from_id]); ?>" rel="author">
            <img class="media-object" src="<?= $model->from->getAvatar('small'); ?>" alt="utf-8">
        </a>
        <span class="read-mark"><?= $model->status == 1 ? '<i id="unrd">Unread</i>' : '<i id="rd">Read</i>'; ?></span>
    </div>
    <div class="media-body">
        <div class="media-heading p-m-username">
            <a class="p-m-u"
               href="<?= Url::to(['/user/profile/show', 'id' => $model->from_id]); ?>"><?= $model->from->username; ?></a>
            <span class="msg-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
            <span class="pull-right"><?= $model->status == 1 ? Yii::t('user', 'Unread') : Yii::t('app', 'Read'); ?> |
            <a href="<?= Url::to(['/user/message/view', 'id' => $model->id]); ?>"><?= Yii::t('app', 'Detail'); ?></a>
        </span>
        </div>
        <div class="media-content"><?= mb_substr(Html::encode($model->message), 0, 100) ?></div>
    </div>
    <?php
} else {
    ?>
    <a class="msg-detail" href="<?= Url::to(['/user/message/view', 'id' => $model->id]); ?>"></a>
    <div class="media-left msg-d-left">
        <a href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]); ?>" rel="author">
            <img class="media-object" src="<?= $model->user->getAvatar('small'); ?>" alt="utf-8">
        </a>
    </div>
    <div class="media-body">
        <div class="media-heading p-m-username">
            <a class="p-m-u"
               href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]); ?>"><?= $model->user->username; ?></a>
            <span class="msg-time"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
            <span class="pull-right"><?= $model->status == 1 ? Yii::t('app', 'Unread') : Yii::t('app', 'Read'); ?> |
            <a href="<?= Url::to(['/user/message/view', 'id' => $model->id]); ?>"><?= Yii::t('app', 'Detail'); ?></a>
        </span>
        </div>
        <div class="media-content"><?= mb_substr(Html::encode($model->message), 0, 100) ?></div>
    </div>


<?php } ?>