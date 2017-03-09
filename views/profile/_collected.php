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
            <small>收藏</small>
        </div>
    </div>
    <div class="summary">
        <ul class="author list-inline">
            <li>
                <a href="<?= Url::to(['/user/profile/view', 'id' => $collection->user_id]) ?>"><?= $collection->user->username ?></a>
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
            <small>收藏</small>
        </div>
    </div>
    <div class="summary">
        <ul class="author list-inline">
            <li>
                <a href="<?= Url::to(['/user/profile/view', 'id' => $collection->user_id]) ?>"><?= $collection->user->username ?></a>
                <span class="split"></span>
                <?= Yii::$app->formatter->asRelativeTime($collection->created_at); ?>
            </li>
        </ul>
        <h2 class="title">
            <a href="<?= Url::to(['/article/article/view', 'id' => $model->model_id]) ?>"><?= Html::encode($model->subject) ?></a>
        </h2>
    </div>
<?php endif; ?>
 