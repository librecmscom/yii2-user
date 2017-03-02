<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\user\models\User;

/**
 * 这个文件和show是一样的，区别是一个是用ID来访问用户主页，一个是用用户名来访问用户主页
 * @var \yii\web\View $this
 * @var  User $model
 */
$this->title = (empty($model->profile->name) ? Html::encode($model->username) : Html::encode($model->profile->name)) . ' Space';
$this->context->layout = 'space';
$this->params['user'] = $model;
?>
<h2 class="h4"><?= Yii::t('user', 'Recently Doing') ?></h2>
<div class="stream-doing clearfix">
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
