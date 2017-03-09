<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yuncms\user\models\Notification;
?>


<section class="stream-list-item <?= $model->status == Notification::STATUS_UNREAD ? 'not_read' : ''; ?>">
    <a href="<?=Url::to(['/user/profile/view', 'id' => $model->user_id])?>"><?=$model->user->username?></a> <?= $model->typeText; ?>
    <?php if (in_array($model->type, ['answer_question', 'follow_question', 'comment_question', 'comment_answer', 'comment_user', 'question_at'])) {
        echo Html::a($model->subject, ['/question/question/view', 'id' => $model->model_id], ['target' => '_blank']);
        if ($model->type == 'comment_answer') {
            echo Yii::t('app', 'in your answer');
        } else if ($model->type == 'comment_user') {
            echo Yii::t('app', 'in your comment');
        } else if ($model->type == 'question_at') {
            echo Yii::t('app', 'mentions you');
        }
    } else if (in_array($model->type, ['comment_article', 'comment_user'])) {
        echo Html::a($model->subject, ['/article/view', 'id' => $model->model_id], ['target' => '_blank']);
        if ($model->type == 'comment_user') {
            echo Yii::t('app', 'in your comment');
        }
    } ?>


    <span class="text-muted ml-10"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
    <?= !empty($model->refer_content) ? Html::tag('blockquote', strip_tags($model->refer_content), ['class' => 'text-fmt']) : ''; ?>
</section>
