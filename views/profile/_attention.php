<?php
use yii\helpers\Url;
use yii\helpers\Html;

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
                <a href="<?= Url::to(['/user/profile/view', 'id' => $attention->id]) ?>"><?= $attention->username; ?></a>
                <div
                    class="stream-following-followed"><?= $attention->userData->supports ?> <?= Yii::t('user', 'Support') ?>
                    / <?= $attention->userData->followers ?><?= Yii::t('user', 'Follower') ?>
                    <?php if (isset($attention->userData->answers)): ?>
                        / <?= $attention->userData->answers ?><?= Yii::t('user', 'Answer') ?>
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
<?php endif; ?>

