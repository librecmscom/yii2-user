<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = Yii::t('user', 'My Message Inbox');
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-md-9 main">
        <h2 class="h3 profile-title">
            <?= Yii::t('user', 'My Message') ?>
            <div class="pull-right">
                <a class="btn btn-primary" href="<?= Url::to(['/user/message/send']); ?>" data-method="post"><?= Yii::t('user', 'New Message'); ?></a>
            </div>
        </h2>
        <div class="row">
            <div class="col-md-12">
                <?= ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemOptions' => ['tag' => 'li', 'class' => 'media'],
                    'itemView' => '_item',//子视图
                    'layout' => "{items}\n{pager}",
                    'options' => [
                        'tag' => 'ul',
                        'class' => 'media-list'
                    ]
                ]); ?>
            </div>
        </div>
    </div>

    <div class="col-xs-12 col-md-3 side">
        <?= $this->render('/_right_menu') ?>
    </div>
</div>
