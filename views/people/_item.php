<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yuncms\user\models\Profile;

/** @var \yii\web\View $this */
/** @var $model \yuncms\user\models\Profile * */


?>
<a class="media-left"
   href="<?= \yii\helpers\Url::to(['/user/profile/view', 'id' => $model->user_id]) ?>" target="_blank">
    <?= Html::img($model->user->getAvatar(), ['class' => 'media-object avatar-82']); ?>
</a>
<div class="media-body">
    <h2 class="media-heading">
        <?= Html::a(empty($model->nickname) ?: $model->user->username, ['/user/profile/view', 'id' => $model->user_id], ['target' => '_blank']); ?>
        <small>
            <!--显示性别图标-->
            <?php
            if ($model->gender == Profile::GENDER_MALE) {
                echo Html::tag('i', '', ['class' => 'fa fa-male', 'title' => '男']);
            } else if ($model->gender == Profile::GENDER_FEMALE) {
                echo Html::tag('i', '', ['class' => 'fa fa-female', 'title' => '女']);
            }
            ?>
            <?= $model->public_email ? Html::tag('i', '', ['class' => 'fa fa-at', 'title' => '邮箱']) : '' ?>
        </small>
    </h2>
    <p><?= HtmlPurifier::process(mb_substr($model->bio, 0, 120, 'utf-8')) ?></p>
</div>
<div class="pull-left" style="margin-top: 10px;">
    <ul class="taglist-inline ib" style="display: inherit;">
        <?php
        foreach ($model->user->tags as $tag) {
            ?>
            <li class="tagPopup">
                <a class="tag"
                   href="<?= Url::to(['/programmer/tag', 'tag' => $tag->name]); ?>"><?= Html::encode($tag->name) ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
