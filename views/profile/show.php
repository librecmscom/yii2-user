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

<button type="button" class="btn mr-10 btn-success" data-target="follow-button" data-source_type="user"
        data-source_id="1" data-show_num="true" data-toggle="tooltip" data-placement="right" title=""
        data-original-title="关注后将获得更新提醒">关注
</button>


<script>
    window.onload=function(){
    /*关注模块处理，用户等*/
    jQuery(document).on('click', '[data-target="follow-button"]', function (e) {
        $(this).button('loading');
        var follow_btn = $(this);
        var source_type = $(this).data('source_type');
        var source_id = $(this).data('source_id');
        var show_num = $(this).data('show_num');
        if (source_type == 'user') {
            follow(source_type,source_id,function(status){
                follow_btn.removeClass('disabled');
                follow_btn.removeAttr('disabled');
                if (status == 'followed') {
                    follow_btn.html('已关注');
                    follow_btn.addClass('active');
                } else {
                    follow_btn.html('关注');
                    follow_btn.removeClass('active');
                }

                /*是否操作关注数*/
                if (Boolean(show_num)) {
                    var follower_num = $("#follower-num").html();
                    if (msg === 'followed') {
                        $("#follower-num").html(parseInt(follower_num) + 1);
                    } else {
                        $("#follower-num").html(parseInt(follower_num) - 1);
                    }
                }
            })
        }
    });
        }
</script>