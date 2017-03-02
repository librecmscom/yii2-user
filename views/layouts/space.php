<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\Modal;
use yuncms\user\models\Profile;
use yuncms\user\widgets\SendMessage;
use frontend\assets\AppAsset;

$appLayouts = Yii::$app->layout;
$asset = AppAsset::register($this);
?>
<?php $this->beginBlock('jumbotron'); ?>
<?php if ($this->params['profile'] instanceof Profile): ?>
    <header class="space-header">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <?= Html::a(Html::img($this->params['profile']->user->getAvatar('big'), ['alt' => Yii::t('user', 'Avatar'), 'class' => 'img-responsive img-circle']), ['/user/profile/show', 'id' => $this->params['profile']->user_id]) ?>
                </div>
                <div class="col-md-7">
                    <div class="space-header-name h3">
                        <?php if (empty($this->params['profile']->nickname)): ?>
                            <?= $this->params['profile']->user->username; ?>
                        <?php else: ?>
                            <?= $this->params['profile']->nickname; ?>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <div class="space-header-social">
                        <span class="space-header-item"><?= Yii::t('user', 'Gender') ?>：
                            <?php if ($this->params['profile']->sex == Profile::SEX_UNCONFIRMED): ?>
                                <i class="fa fa-genderless"></i>
                            <?php elseif ($this->params['profile']->sex == Profile::SEX_MALE): ?>
                                <i class="fa fa-mars"></i>
                            <?php elseif ($this->params['profile']->sex == Profile::SEX_FEMALE): ?>
                                <i class="fa fa-venus"></i>
                            <?php endif; ?>
                        </span>
                        <?php if (!empty($this->params['profile']->location)): ?>
                            <span class="space-header-item">
                            <i class="fa fa-map-marker"></i> <?= Html::encode($this->params['profile']->location) ?> </span>
                        <?php endif; ?>

                        <?php if (!empty($this->params['profile']->website)): ?>
                            <span class="space-header-item">
                            <i class="fa fa-link"></i> <a href="<?= $this->params['profile']->website ?>"
                                                          rel="nofollow"><?= Html::encode($this->params['profile']->website) ?></a></span>
                        <?php endif; ?>
                        <?php if (!empty($this->params['profile']->public_email)): ?>
                            <span class="space-header-item">
                            <i class="fa fa-envelope-o"></i> <?= Html::mailto(Html::encode($this->params['profile']->public_email), Html::encode($this->params['profile']->public_email)) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <div class="space-header-desc mt-15">
                        <?php if (!empty($this->params['profile']->bio)): ?>
                            <p><?= Html::encode($this->params['profile']->bio) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="space-header-social mt-15">
                        <?= Url::to(['/user/profile/view', 'username' => $this->params['profile']->user->username], true); ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mt-10">
                        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->isFollowed(get_class($this->params['profile']->user), $this->params['profile']->user_id)): ?>
                            <button type="button" class="btn mr-10 btn-success active" data-target="follow-button"
                                    data-source_type="user"
                                    data-source_id="<?= $this->params['profile']->user_id; ?>" data-show_num="true"
                                    data-toggle="tooltip" data-placement="right" title=""
                                    data-original-title="<?= Yii::t('user', 'Follow will be updated to remind') ?>"><?= Yii::t('user', 'Followed') ?>
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn mr-10 btn-success" data-target="follow-button"
                                    data-source_type="user"
                                    data-source_id="<?= $this->params['profile']->user_id; ?>" data-show_num="true"
                                    data-toggle="tooltip" data-placement="right" title=""
                                    data-original-title="<?= Yii::t('user', 'Follow will be updated to remind') ?>"><?= Yii::t('user', 'Follow') ?>
                            </button>
                        <?php endif; ?>

                        <?php
                        if (!Yii::$app->user->isGuest) {
                            Modal::begin([
                                'header' => Yii::t('user', 'Send message to') .'  '. $this->params['profile']->user->username,
                                'toggleButton' => [
                                    'tag' => 'button',
                                    'class' => 'btn btn-default btnMessageTo',
                                    'label' => Yii::t('user', 'Message'),
                                ],
                            ]);
                            ?>
                            <?= SendMessage::widget(['username' => $this->params['profile']->user->username]); ?>
                            <?php Modal::end();
                        } ?>

                    </div>
                    <div class="space-header-info row mt-30">
                        <div class="col-md-4">
                            <span class="h3">
                                <a href="<?= Url::to(['/user/profile/coins', 'id' => $this->params['profile']->user_id]) ?>"><?= $this->params['profile']->user->userData->coins; ?></a>
                            </span>
                            <span><?= Yii::t('user', 'Coins') ?></span>
                        </div>
                        <div class="col-md-4">
                            <span class="h3"><a
                                    href="<?= Url::to(['/user/profile/credits', 'id' => $this->params['profile']->user_id]) ?>"><?= $this->params['profile']->user->userData->credits; ?></a></span>
                            <span><?= Yii::t('user', 'Credits') ?></span>
                        </div>
                        <div class="col-md-4">
                            <span class="h3">
                                <a id="follower-num"
                                   href="<?= Url::to(['/user/profile/followers', 'id' => $this->params['profile']->user_id]) ?>"><?= $this->params['profile']->user->userData->followers; ?></a>
                            </span>
                            <span><?= Yii::t('user', 'Fans') ?></span>
                        </div>
                    </div>
                    <div class="mt-10 border-top" style="color:#999;padding-top:10px; ">
                        <i class="fa fa-paw"></i> <?= Yii::t('user', '{n, plural, =0{No visitors} =1{One visitor} other{# visitors}}', ['n' => $this->params['profile']->user->userData->views]); ?>
                    </div>
                    <div class="mt-10 border-top" style="color:#999;">
                        <i class="fa fa-clock-o"></i> <?= Yii::t('user', 'Joined on {0, date}', $this->params['profile']->user->created_at) ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
<?php endif; ?>
<?php $this->endBlock(); ?>

<?php $this->beginContent('@app/views/layouts/' . $appLayouts . '.php') ?>
<div class="row mt-30">
    <div class="col-md-2">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $this->params['profile']->user_id) {//Me
            $menuItems = [
                ['label' => Yii::t('user', 'My Page'), 'url' => ['/user/profile/index']],
                //问答
                ['label' => Yii::t('app', 'My answer'), 'url' => ['/user/profile/answer'], 'visible' => Yii::$app->hasModule('question')],
                ['label' => Yii::t('app', 'Question'), 'url' => ['/user/profile/question'], 'visible' => Yii::$app->hasModule('question')],
                //文章
                ['label' => Yii::t('app', 'Note'), 'url' => ['/note/note/index'], 'visible' => Yii::$app->hasModule('article')],

                '<li role="separator" class="divider"></li>',

                ['label' => '我的金币', 'url' => ['/tag/index']],
                ['label' => '我的经验', 'url' => ['/site/about']],
                ['label' => '我的粉丝', 'url' => ['/site/contact']],
                ['label' => '我的关注', 'url' => ['/site/contact']],
                ['label' => '我的收藏', 'url' => ['/user/profile/collect', 'id' => Yii::$app->user->id]],
            ];
        } else {//he
            $menuItems = [
                ['label' => Yii::t('app', 'His Page'), 'url' => ['/user/profile/show', 'id' => $this->params['profile']->user_id]],
                //问答
                ['label' => Yii::t('app', 'My answer'), 'url' => ['/article/article/index'], 'visible' => Yii::$app->hasModule('question')],
                ['label' => Yii::t('app', 'Question'), 'url' => ['/question/question/index'], 'visible' => Yii::$app->hasModule('question')],

                //文章
                ['label' => Yii::t('app', 'Note'), 'url' => ['/note/note/index'], 'visible' => Yii::$app->hasModule('article')],

                '<li role="separator" class="divider"></li>',

                ['label' => '我的金币', 'url' => ['/tag/index']],
                ['label' => '我的经验', 'url' => ['/site/about']],
                ['label' => '我的粉丝', 'url' => ['/site/contact']],
                ['label' => '我的关注', 'url' => ['/site/contact']],
                ['label' => '我的收藏', 'url' => ['/user/profile/collect', 'id' => $this->params['profile']->user_id]],
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
