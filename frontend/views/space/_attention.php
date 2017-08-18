<?php
use yii\helpers\Url;
use yii\helpers\Html;

/** * @var \yii\web\View $this */
/** @var string $type */
?>
<?php if ($type == 'questions'): ?>
    <?php $attention = \yuncms\question\models\Question::findOne($model->model_id) ?>
    <div class="row">
        <div class="col-md-10">
            <a class="stream-following-title"
               href="<?= Url::to(['/question/question/view', 'id' => $attention->id]) ?>"><?= Html::encode($attention->title); ?></a>
        </div>
        <div class="col-md-2 text-right">
            <span class="stream-following-followed mr-10"><?= $attention->followers ?> <?= Yii::t('user', 'Follower') ?></span>

            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed(get_class($attention), $model->model_id)): ?>
                <button type="button" class="btn btn-default btn-xs active" data-target="follow-button"
                        data-source_type="question"
                        data-source_id="<?= $model->model_id; ?>"><?= Yii::t('user', 'Followed') ?>
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-default btn-xs" data-target="follow-button"
                        data-source_type="question"
                        data-source_id="<?= $model->model_id; ?>"><?= Yii::t('user', 'Follow') ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php elseif ($type == 'users'): ?>
    <?php $attention = \yuncms\user\models\User::findOne($model->model_id) ?>
    <div class="row">
        <div class="col-md-10">
            <img class="avatar-32" src="<?= $attention->getAvatar('middle') ?>"/>
            <div>
                <a href="<?= Url::to(['/user/space/view', 'id' => $attention->id]) ?>"><?= $attention->username; ?></a>
                <div
                        class="stream-following-followed"><?= $attention->extend->supports ?> <?= Yii::t('user', 'Support') ?>
                    / <?= $attention->extend->followers ?><?= Yii::t('user', 'Follower') ?>
                    <?php if (isset($attention->extend->answers)): ?>
                        / <?= $attention->extend->answers ?><?= Yii::t('user', 'Answer') ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-2 text-right">
            <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed(get_class($attention), $model->model_id)): ?>
                <button type="button" class="btn btn-default btn-xs followerUser active" data-target="follow-button"
                        data-source_type="user"
                        data-source_id="<?= $model->model_id; ?>"><?= Yii::t('user', 'Followed') ?>
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-default followerUser btn-xs" data-target="follow-button"
                        data-source_type="user"
                        data-source_id="<?= $model->model_id; ?>"><?= Yii::t('user', 'Follow') ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
<?php elseif ($type == 'lives'): ?>
    <?php
    $attention = \yuncms\live\models\Stream::findOne($model->model_id);
    ?>
    <a href="<?= Url::to(['/live/stream/view', 'uuid' => $attention->uuid]); ?>" target="_blank" style="display:block;">
        <div class="live-avatar pull-left">
            <img class="img-rounded avatar-64" src="<?= $attention->user->getAvatar() ?>">
        </div>
        <div class="live-content">
            <h2 class="live-title">
                <?= Html::encode($attention->title) ?>
            </h2>
            <div class="live-info">
                <object><a href="<?= Url::to(['/user/space/view', 'id' => $attention->user_id]) ?>"><span
                                class="program-info-name"><?= $attention->user->username; ?></span></a></object>
                ·
                <span class="live-info-number"><?= $attention->applicants ?> 人参与</span>
            </div>
            <div class="live-state">
                <div class="will">
                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                    <span> <?= Yii::$app->formatter->asRelativeTime($attention->start_time); ?></span>
                </div>
            </div>
        </div>
    </a>

<?php endif; ?>

