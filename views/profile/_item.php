<?php
/**
 * var $model \yuncms\user\models\Doing   单条消息通知实例
 */
use yii\helpers\Html;
 ?>
<section class="stream-doing-item">
    <p class="stream-doing-item-info"><?= Yii::$app->formatter->asRelativeTime($model->created_at); ?> <?= $model->actionText; ?></p>
    <div class="stream-doing-item-title">
        <?php
        if (in_array($model->action, ['ask','answer','follow_question', 'answer_question', 'ask_question', 'append_reward', 'answer_adopted'])) {
            echo Html::beginTag('h4');
            echo Html::a($model->subject, ['/question/question/view', 'id' => $model->source_id]);
            echo Html::endTag('h4');
        } else if (in_array($model->action, ['upload_code','download_code','view_code'])) {
            echo Html::beginTag('h4');
            echo Html::a($model->subject, '/article/'. $model->source_id);
            echo Html::endTag('h4');
        } else if (in_array($model->action, ['create_article'])) {
            echo Html::beginTag('h4');
            echo Html::a($model->subject, '/article/'.$model->source_id);
            echo Html::endTag('h4');
        }else if (in_array($model->action, ['follow_user'])) {
            echo Html::beginTag('h4');
            echo Html::a($model->subject, ['/user/profile/show', 'id' => $model->source_id]);
            echo Html::endTag('h4');
        }
        ?>
    </div>
    <?php
    if (in_array($model->action, ['answer','answer_question'])) {
        ?>
        <p class="stream-doing-item-quote">
            <?= $model->content; ?>
        </p>
    <?php } ?>
</section>
