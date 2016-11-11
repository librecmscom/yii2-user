<?php

use yii\helpers\Html;
use yuncms\user\models\Profile;

/**
 * @var \yii\web\View $this
 * @var \yuncms\user\models\Profile $model
 */
$this->title = empty($model->name) ? Html::encode($model->user->username) : Html::encode($model->name);
//$this->context->layout = '//space';
?>
<header class="space-header">
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <?= Html::a(Html::img($model->user->getAvatar('big'), ['alt' => Yii::t('user', 'Avatar'), 'class' => 'img-responsive img-circle']), ['/user/profile/show', 'id' => $model->user_id]) ?>
            </div>
            <div class="col-md-7">
                <div class="space-header-name h3">
                    <?= $this->title ?>
                </div>
                <hr>
                <div class="space-header-social">
                    <?php if ($model->sex == Profile::SEX_UNCONFIRMED): ?>
                        <span class="space-header-item">性别：  <i class="fa fa-genderless"></i> </span>
                    <?php elseif ($model->sex == Profile::SEX_MALE): ?>
                        <span class="space-header-item">性别：  <i class="fa fa-mars"></i> </span>
                    <?php elseif ($model->sex == Profile::SEX_FEMALE): ?>
                        <span class="space-header-item">性别：  <i class="fa fa-venus"></i> </span>
                    <?php endif; ?>

                    <?php if (!empty($model->location)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-map-marker"></i> <?= Html::encode($model->location) ?> </span>
                    <?php endif; ?>

                    <?php if (!empty($model->website)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-link"></i> <?= Html::a(Html::encode($model->website), Html::encode($model->website)) ?></span>
                    <?php endif; ?>
                    <?php if (!empty($model->public_email)): ?>
                        <span class="space-header-item">
                            <i class="fa fa-envelope-o"></i> <?= Html::a(Html::encode($model->public_email), 'mailto:' . Html::encode($model->public_email)) ?>
                        </span>
                    <?php endif; ?>
                    <span class="space-header-item">
                        <i class="fa fa-clock-o"></i> <?= Yii::t('user', 'Joined on {0, date}', $model->user->created_at) ?>
                    </span>
                </div>
                <div class="space-header-desc mt-15">
                    <?php if (!empty($model->bio)): ?>
                        <p><?= Html::encode($model->bio) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mt-10">

                </div>

                <div class="mt-10 border-top" style="color:#999;padding-top:10px; ">
                    <i class="fa fa-paw"></i> 主页被访问 <?= $model->user->userData->views ?> 次
                </div>
            </div>
        </div>
    </div>
</header>
