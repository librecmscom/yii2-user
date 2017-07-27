<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yuncms\user\models\Profile;

/** @var \yii\web\View $this */
/** @var $model \yuncms\user\models\User * */


?>
<a class="media-left"
   href="<?= \yii\helpers\Url::to(['/user/space/view', 'id' => $model->id]) ?>" target="_blank">
    <?= Html::img($model->getAvatar(), ['class' => 'media-object avatar-82']); ?>
</a>
<div class="media-body">
    <h2 class="media-heading">
        <?= Html::a(Html::encode($model->name), ['/user/space/view', 'id' => $model->id], ['target' => '_blank']); ?>
        <small>
            <!--显示性别图标-->
            <?php
            if ($model->profile->gender == Profile::GENDER_MALE) {
                echo Html::tag('i', '', ['class' => 'fa fa-male', 'title' => '男']);
            } else if ($model->profile->gender == Profile::GENDER_FEMALE) {
                echo Html::tag('i', '', ['class' => 'fa fa-female', 'title' => '女']);
            }
            ?>
            <?= $model->profile->email ? Html::tag('i', '', ['class' => 'fa fa-at', 'title' => '邮箱']) : '' ?>
        </small>
    </h2>
    <p><?= HtmlPurifier::process(mb_substr($model->profile->bio, 0, 120, 'utf-8')) ?></p>
</div>
<div class="pull-left" style="margin-top: 10px;">
    <ul class="taglist-inline ib" style="display: inherit;">
        <?php
        foreach ($model->tags as $tag) {
            ?>
            <li class="tagPopup">
                <a class="tag"
                   href="<?= Url::to(['/user/people/tag', 'tag' => $tag->name]); ?>"><?= Html::encode($tag->name) ?></a>
            </li>
            <?php
        }
        ?>
    </ul>
</div>
