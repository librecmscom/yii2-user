<?php

/**
 * @var \yii\web\View $this
 * @var string $content
 * @var \yuncms\user\models\User $user
 */
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yuncms\user\models\Profile;
use yuncms\user\widgets\SendMessage;
use frontend\assets\AppAsset;

$user = $this->params['user'];
$appLayouts = Yii::$app->layout;
$asset = AppAsset::register($this);
?>
<?php $this->beginBlock('jumbotron'); ?>
<header class="space-header">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <?= Html::a(Html::img($user->getAvatar('big'), ['alt' => Yii::t('user', 'Avatar'), 'class' => 'img-responsive img-circle']), ['/user/profile/view', 'id' => $user->id]) ?>
            </div>
            <div class="col-md-7">
                <div class="space-header-name h3">
                    <?php if (empty($user->profile->nickname)): ?>
                        <?= $user->username; ?>
                    <?php else: ?>
                        <?= $user->profile->nickname; ?>
                    <?php endif; ?>
                </div>
                <hr>
                <div class="space-header-social">
                        <span class="space-header-item"><?= Yii::t('user', 'Gender') ?>：
                            <?php if ($user->profile->gender == Profile::GENDER_UNCONFIRMED): ?>
                                <i class="fa fa-genderless"></i>
                            <?php elseif ($user->profile->gender == Profile::GENDER_MALE): ?>
                                <i class="fa fa-mars"></i>
                            <?php elseif ($user->profile->gender == Profile::GENDER_FEMALE): ?>
                                <i class="fa fa-venus"></i>
                            <?php endif; ?>
                        </span>
                    <?php if (!empty($user->profile->location)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-map-marker"></i> <?= Html::encode($user->profile->location) ?> </span>
                    <?php endif; ?>

                    <?php if (!empty($user->profile->website)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-link"></i> <a href="<?= $user->profile->website ?>"
                                                          rel="nofollow"><?= Html::encode($user->profile->website) ?></a></span>
                    <?php endif; ?>
                    <?php if (!empty($user->profile->public_email)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-envelope-o"></i> <?= Html::mailto(Html::encode($user->profile->public_email), Html::encode($user->profile->public_email)) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <div class="space-header-desc mt-15">
                    <?php if (!empty($user->profile->bio)): ?>
                        <p><?= Html::encode($user->profile->bio) ?></p>
                    <?php endif; ?>
                </div>

                <div class="space-header-social mt-15">
                    <?= Url::to(['/user/profile/show', 'username' => $user->profile->user->username], true); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mt-10">
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed(get_class($user), $user->id)): ?>
                        <button type="button" class="btn mr-10 btn-success active" data-target="follow-button"
                                data-source_type="user"
                                data-source_id="<?= $user->id; ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title=""
                                data-original-title="<?= Yii::t('user', 'Follow will be updated to remind') ?>"><?= Yii::t('user', 'Followed') ?>
                        </button>
                    <?php else: ?>
                        <button type="button" class="btn mr-10 btn-success" data-target="follow-button"
                                data-source_type="user"
                                data-source_id="<?= $user->id; ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title=""
                                data-original-title="<?= Yii::t('user', 'Follow will be updated to remind') ?>"><?= Yii::t('user', 'Follower') ?>
                        </button>
                    <?php endif; ?>

                    <?php
                    if (!Yii::$app->user->isGuest) {
                        Modal::begin([
                            'header' => Yii::t('user', 'Send message to') . '  ' . $user->username,
                            'toggleButton' => [
                                'tag' => 'button',
                                'class' => 'btn btn-default btnMessageTo',
                                'label' => Yii::t('user', 'Message'),
                            ],
                        ]);
                        ?>
                        <?= SendMessage::widget(['username' => $user->username]); ?>
                        <?php Modal::end();
                    } ?>

                </div>
                <div class="space-header-info row mt-30">
                    <div class="col-md-4">
                            <span class="h3">
                                <a href="<?= Url::to(['/user/profile/coin', 'id' => $user->id]) ?>"><?= $user->userData->coins; ?></a>
                            </span>
                        <span><?= Yii::t('user', 'Coins') ?></span>
                    </div>
                    <div class="col-md-4">
                            <span class="h3"><a
                                    href="<?= Url::to(['/user/profile/credit', 'id' => $user->id]) ?>"><?= $user->userData->credits; ?></a></span>
                        <span><?= Yii::t('user', 'Credits') ?></span>
                    </div>
                    <div class="col-md-4">
                            <span class="h3">
                                <a id="follower-num"
                                   href="<?= Url::to(['/user/profile/follower', 'id' => $user->id]) ?>"><?= $user->userData->followers; ?></a>
                            </span>
                        <span><?= Yii::t('user', 'Fans') ?></span>
                    </div>
                </div>
                <div class="mt-10 border-top" style="color:#999;padding-top:10px; ">
                    <i class="fa fa-paw"></i> <?= Yii::t('user', '{n, plural, =0{No visitors} =1{One visitor} other{# visitors}}', ['n' => $user->userData->views]); ?>
                </div>
                <div class="mt-10 border-top" style="color:#999;">
                    <i class="fa fa-clock-o"></i> <?= Yii::t('user', 'Joined on {0, date}', $user->created_at) ?>
                </div>
            </div>
        </div>
    </div>
</header>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/' . $appLayouts . '.php') ?>
<div class="row mt-30">
    <div class="col-md-2">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $user->id) {//Me
            $menuItems = [
                ['label' => Yii::t('user', 'My Page'), 'url' => ['/user/profile/index']],
                //问答
                ['label' => Yii::t('user', 'My Answer'), 'url' => ['/user/profile/answer', 'id' => $user->id], 'visible' => Yii::$app->hasModule('question')],
                ['label' => Yii::t('user', 'My Question'), 'url' => ['/user/profile/question', 'id' => $user->id], 'visible' => Yii::$app->hasModule('question')],
                //文章
                ['label' => Yii::t('user', 'My Note'), 'url' => ['/note/manage/index'], 'visible' => Yii::$app->hasModule('article')],

                '<li role="separator" class="divider"></li>',

                ['label' => Yii::t('user', 'My Coin'), 'url' => ['/user/profile/coin', 'id' => $user->id]],
                ['label' => Yii::t('user', 'My Credit'), 'url' => ['/user/profile/credit', 'id' => $user->id]],
                ['label' => Yii::t('user', 'My Follower'), 'url' => ['/user/profile/follower', 'id' => $user->id]],
                ['label' => Yii::t('user', 'I\'m Following'), 'url' => ['/user/profile/attention', 'id' => $user->id, 'type' => 'questions']],
                ['label' => Yii::t('user', 'My Favorites'), 'url' => ['/user/profile/collected', 'id' => $user->id, 'type' => 'questions']],
            ];
        } else {//he
            $menuItems = [
                ['label' => Yii::t('user', 'His Page'), 'url' => ['/user/profile/view', 'id' => $user->id]],
                //问答
                ['label' => Yii::t('user', 'His Answer'), 'url' => ['/article/article/index'], 'visible' => Yii::$app->hasModule('question')],
                ['label' => Yii::t('user', 'His Question'), 'url' => ['/question/question/index'], 'visible' => Yii::$app->hasModule('question')],

                //文章
                ['label' => Yii::t('user', 'His Note'), 'url' => ['/note/note/index', 'user_id' => $user->id], 'visible' => Yii::$app->hasModule('article')],

                '<li role="separator" class="divider"></li>',

                ['label' => Yii::t('user', 'His Coin'), 'url' => ['/user/profile/coin', 'id' => $user->id]],
                ['label' => Yii::t('user', 'His Credit'), 'url' => ['/user/profile/credit', 'id' => $user->id]],
                ['label' => Yii::t('user', 'His Follower'), 'url' => ['/user/profile/follower', 'id' => $user->id]],
                ['label' => Yii::t('user', 'His Followed'), 'url' => ['/user/profile/attention', 'id' => $user->id, 'type' => 'questions']],
                ['label' => Yii::t('user', 'His Collect'), 'url' => ['/user/profile/collected', 'id' => $user->id, 'type' => 'questions']],
            ];
        } ?>
        <?= Nav::widget([
            'options' => ['class' => 'nav nav-pills nav-stacked space-nav'],
            'activateParents' => true,
            'items' => $menuItems,
        ]); ?>
    </div>
    <div class="col-md-10">
        <?= $content ?>
    </div>
</div>


<?php $this->endContent() ?>
