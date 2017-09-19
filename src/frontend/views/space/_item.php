<?php
/**
 * var $model \yuncms\user\models\Doing   单条消息通知实例
 */
use yii\helpers\Html;

?>
<section class="stream-doing-item">
    <p class="stream-doing-item-info"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?> <?= $model->actionText; ?></p>
    <div class="stream-doing-item-title">
        <h4>
            <?php if (in_array($model->action, ['ask', 'answer', 'follow_question', 'answer_question', 'ask_question', 'append_reward', 'answer_adopted'])): ?>
                <?= Html::a($model->subject, ['/question/question/view', 'id' => $model->model_id]) ?>
            <?php elseif (in_array($model->action, ['create_article'])): ?>
                <?= Html::a($model->subject, ['/article/article/view', 'id' => $model->model_id]); ?>
            <?php elseif (in_array($model->action, ['follow_user'])): ?>
                <?= Html::a($model->subject, ['/user/space/view', 'id' => $model->model_id]); ?>
            <?php elseif (in_array($model->action, ['create_note'])): ?>
                <?= Html::a($model->subject, ['/note/note/view', 'id' => $model->model_id]); ?>
            <?php elseif (in_array($model->action, ['create_live'])): ?>
                <?= Html::a($model->subject, ['/live/stream/view', 'id' => $model->model_id]); ?>
            <?php endif; ?>
        </h4>
    </div>
    <?php if (in_array($model->action, ['answer', 'answer_question'])): ?>
        <p class="stream-doing-item-quote">
            <?= $model->content; ?>
        </p>
    <?php endif; ?>
</section>
