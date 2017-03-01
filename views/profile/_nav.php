<?php
use yii\helpers\Url;
?>
<div class="col-md-2">
    <ul class="nav nav-pills nav-stacked space-nav">
        <?php if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->user_id): ?>
            <li class="active"><a href="<?= Url::to(['/user/profile/index']); ?>"><?= Yii::t('user', 'My Page') ?></a></li>
        <?php else: ?>
            <li class="active"><a href="<?= Url::to(['/user/profile/show', 'id' => $model->user_id]); ?>"><?= Yii::t('user', 'His Page') ?></a></li>
        <?php endif; ?>

        <?php if(Yii::$app->hasModule('question')):?>
            <li><a href="https://wenda.tipask.com/people/83/answers">我的回答</a></li>
            <li><a href="https://wenda.tipask.com/people/83/questions">我的提问</a></li>
        <?php endif; ?>

        <?php if(Yii::$app->hasModule('article')):?>
            <li><a href="https://wenda.tipask.com/people/83/articles">我的文章</a></li>
        <?php endif; ?>
        <li role="separator" class="divider"><a></a></li>
        <li><a href="https://wenda.tipask.com/people/83/coins">我的金币</a></li>
        <li><a href="https://wenda.tipask.com/people/83/credits">我的经验</a></li>
        <li><a href="https://wenda.tipask.com/people/83/followers">我的粉丝</a></li>
        <li><a href="https://wenda.tipask.com/people/83/followed/questions">我的关注</a></li>
        <li><a href="https://wenda.tipask.com/people/83/collected/questions">我的收藏</a></li>
    </ul>
</div>
<!-- Nav tabs -->
