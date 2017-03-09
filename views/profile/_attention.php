<?php
use yii\helpers\Url;
use yii\helpers\Html;

/** @var string $type */
?>
<?php if ($type == 'questions'): ?>
    <?php $attention = \yuncms\question\models\Question::findOne($model->model_id) ?>
    <li>
        <div class="row">
            <div class="col-md-10">
                <a class="stream-following-title"
                   href="<?= Url::to(['/question/question/view', 'id' => $attention->id]) ?>"><?= Html::encode($attention->title); ?></a>
            </div>
            <div class="col-md-2 text-right">
                <span class="stream-following-followed mr-10"><?=$attention->followers?> 关注</span>

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
    </li>
<?php elseif ($type == 'users'): ?>
    <?php $attention = \yuncms\user\models\User::findOne($model->model_id) ?>
    <li>
        <div class="row">
            <div class="col-md-10">
                <img class="avatar-32" src="<?= $attention->getAvatar('middle') ?>"/>
                <div>
                    <a href="<?= Url::to(['/user/profile/view', 'id' => $attention->id]) ?>"><?= $attention->username; ?></a>
                    <div class="stream-following-followed"><?= $attention->userData->supports ?> 赞同
                        / <?= $attention->userData->followers ?>关注
                        <?php if (isset($attention->userData->answers)): ?>
                            / <?= $attention->userData->answers ?>回答
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
    </li>
<?php elseif ($type == 'streams'): ?>
    <?php $attention = \common\models\Stream::findOne($model->model_id) ?>
    <li>
        <div class="thumbnail">
            <a class="img-box" href="<?= Url::to(['stream/view', 'id' => $attention->id]); ?>">
                <img class="live-shot" src="<?= $attention->thumbnailUrl ?>"
                     alt="<?= Html::encode($attention->title) ?>" onerror="javascript:this.src='/img/default_img.svg';">
                <div class="play-icon"><em><span class="caret"></span></em></div>
                <div class="live-user">
                    <img class="avatar-35 mr-10 pull-left" src="<?= $model->user->getAvatar() ?>"
                         alt="<?= $attention->user->username; ?>"
                         onerror="javascript:this.src='/img/no_avatar_small.gif';">
                    <div class="pull-left user-name"><?= $attention->user->username; ?></div>
                    <?php if ($attention->isLive()): ?>
                        <span class="live-status pull-right">
                    <span class="pull-left"><?= Yii::t('app', 'Live') ?></span><span class="rec-icon"></span>
                </span>
                    <?php endif; ?>
                    <div class="online-num"><span class="fa fa-user"></span> <?= $attention->online_users ?></div>
                </div>
                <p><?= empty($model->description) ? Yii::t('app', 'No introduction') : Html::encode($attention->description); ?></p>
            </a>
            <div class="vd-info">
                <h3><a href="<?= Url::to(['stream/view', 'id' => $model->id]); ?>"
                       title="<?= Html::encode($attention->title) ?>"><?= Html::encode($attention->title) ?></a></h3>
                <ul class="u-tags">
                    <?php foreach ($attention->categories as $category): ?>
                        <li>
                            <a href="<?= Url::to(['/stream/index', 'category' => $category->name]) ?>"><?= Html::encode($category->name) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </li>
<?php endif; ?>

