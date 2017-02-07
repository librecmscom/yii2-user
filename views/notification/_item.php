<?php
use yii\helpers\Html;
?>


<section class="stream-list-item <?= $model->status == '10' ? 'not_read' : ''; ?>">
    <?= Html::a($model->user->username, ['/user/profile/show', 'id' => $model->user_id]); ?>
    <?= $model->typeText; ?>
    <?php if (in_array($model->type, ['answer_question', 'follow_question', 'comment_question', 'comment_answer', 'comment_user', 'question_at'])) {
        echo Html::a($model->subject, ['/question/view', 'id' => $model->source_id], ['target' => '_blank']);
        if ($model->type == 'comment_answer') {
            echo Yii::t('app', 'in your answer');
        } else if ($model->type == 'comment_user') {
            echo Yii::t('app', 'in your comment');
        } else if ($model->type == 'question_at') {
            echo Yii::t('app', 'mentions you');
        }
    } else if (in_array($model->type, ['comment_article', 'comment_user'])) {
        echo Html::a($model->subject, ['/article/view', 'id' => $model->source_id], ['target' => '_blank']);
        if ($model->type == 'comment_user') {
            echo Yii::t('app', 'in your comment');
        }
    } else if (in_array($model->type, ['download_code', 'upload_code', 'comment_code'])) {
        echo Html::a($model->subject, '/article/' . $model->source_id, ['target' => '_blank']);
    } ?>
    <span class="text-muted ml-10"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
    <?= !empty($model->refer_content) ? Html::tag('blockquote', strip_tags($model->refer_content), ['class' => 'text-fmt']) : ''; ?>
</section>
