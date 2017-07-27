<?php

use yii\helpers\Url;
use yii\helpers\Html;
/**
 * @var string $type
 */
?>

<?php if ($type == 'questions'): ?>
    <?php $collection = \yuncms\question\models\Question::findOne($model->model_id) ?>
    <div class="bookmark-rank">
        <div class="collections">
            <?= $collection->collections ?>
            <small><?=Yii::t('user','Collection')?></small>
        </div>
    </div>
    <div class="summary">
        <ul class="author list-inline">
            <li>
                <a href="<?= Url::to(['/user/space/view', 'id' => $collection->user_id]) ?>"><?= $collection->user->name ?></a>
                <span class="split"></span>
                <?= Yii::$app->formatter->asRelativeTime($collection->created_at); ?>
            </li>
        </ul>
        <h2 class="title">
            <a href="<?= Url::to(['/question/question/view', 'id' => $model->model_id]) ?>"><?= Html::encode($model->subject) ?></a>
        </h2>
    </div>
<?php elseif ($type == 'articles'): ?>
    <?php $collection = \yuncms\article\models\Article::findOne($model->model_id) ?>
    <div class="bookmark-rank">
        <div class="collections">
            <?= $collection->collections ?>
            <small><?=Yii::t('user','Collection')?></small>
        </div>
    </div>
    <div class="summary">
        <ul class="author list-inline">
            <li>
                <a href="<?= Url::to(['/user/space/view', 'id' => $collection->user_id]) ?>"><?= $collection->user->name ?></a>
                <span class="split"></span>
                <?= Yii::$app->formatter->asRelativeTime($collection->created_at); ?>
            </li>
        </ul>
        <h2 class="title">
            <a href="<?= Url::to(['/article/article/view', 'id' => $model->model_id]) ?>"><?= Html::encode($model->subject) ?></a>
        </h2>
    </div>
<?php elseif ($type == 'lives'): ?>
    <?php
    $collection = \yuncms\live\models\Stream::findOne($model->model_id);
    ?>
    <a href="<?= Url::to(['/live/stream/view', 'uuid' => $collection->uuid]); ?>" target="_blank" style="display:block;">
        <div class="live-avatar pull-left">
            <img class="img-rounded avatar-64" src="<?= $collection->user->getAvatar() ?>">
        </div>
        <div class="live-content">
            <h2 class="live-title">
                <?= Html::encode($collection->title) ?>
            </h2>
            <div class="live-info">
                <object><a href="<?= Url::to(['/user/space/view', 'id' => $collection->user_id]) ?>"><span
                                class="program-info-name"><?= $collection->user->name; ?></span></a></object>
                ·
                <span class="live-info-number"><?= $collection->applicants ?> 人参与</span>
            </div>
            <div class="live-state">
                <div class="will">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span> <?= Yii::$app->formatter->asRelativeTime($collection->start_time); ?></span>
                </div>
            </div>
        </div>
    </a>
<?php endif; ?>
 