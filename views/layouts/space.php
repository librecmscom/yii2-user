<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use yuncms\user\models\Profile;
use common\widgets\Alert;
use frontend\assets\AppAsset;

$asset = AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?= Html::tag('title', Html::encode($this->title)); ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!-- Header
================================================== -->
<header class="top-common-nav">
    <!-- Nav -->
    <?= $this->render(
        '//layouts/_nav.php', ['asset' => $asset]
    ) ?>
</header>
<!-- Main
================================================== -->
<div class="wrap mt-60">
    <?php if($this->params['profile'] instanceof Profile):?>
    <header class="space-header">
        <div class="container">
            <div class="row">
                <div class="col-md-2">
                    <?= Html::a(Html::img($this->params['profile']->user->getAvatar('big'), ['alt' => Yii::t('user', 'Avatar'), 'class' => 'img-responsive img-circle']), ['/user/profile/show', 'id' => $this->params['profile']->user_id]) ?>
                </div>
                <div class="col-md-7">
                    <div class="space-header-name h3">
                        <?= $this->params['profile']->user->username; ?>
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
                </div>
                <div class="col-md-3">
                    <div class="mt-10">
                        <button type="button" class="btn mr-10 btn-success" data-target="follow-button"
                                data-source_type="user"
                                data-source_id="<?= $this->params['profile']->user_id; ?>" data-show_num="true"
                                data-toggle="tooltip" data-placement="right" title=""
                                data-original-title="<?= Yii::t('user', 'Follow will be updated to remind') ?>"><?= Yii::t('user', 'Follow') ?>
                        </button>

                        <button class="btn btn-default btnMessageTo" data-toggle="modal"
                                data-target="#sendTo_message_model"
                                data-to_user_id="<?= $this->params['profile']->user_id; ?>"
                                data-to_user_name="<?= $this->params['profile']->user->username; ?>">
                            <?= Yii::t('user', 'Send messages') ?>
                        </button>
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
                                <a id="follower-num" href="<?= Url::to(['/user/profile/followers', 'id' => $this->params['profile']->user_id]) ?>"><?= $this->params['profile']->user->userData->followers; ?></a>
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
    <div class="container">
        <!--[if lt IE 9]>
        <div class="alert alert-danger topframe" role="alert">你的浏览器实在<strong>太太太太太太旧了</strong>，放学别走，升级完浏览器再说
            <a target="_blank" class="alert-link" href="http://browsehappy.com">立即升级</a>
        </div>
        <![endif]-->
        <?= Alert::widget() ?>
        <?= $content ?>
    </div><!-- /.container -->
</div><!-- /.wrap -->

<!-- Modal
================================================== -->
<?= Modal::widget(); ?>

<!-- Footer
================================================== -->
<?= $this->render(
    '//layouts/_footer.php', ['asset' => $asset]
) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
