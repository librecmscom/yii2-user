<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yuncms\user\models\Profile;

/**
 * 这个文件和show是一样的，区别是一个是用ID来访问用户主页，一个是用用户名来访问用户主页
 * @var \yii\web\View $this
 * @var  Profile $model
 */
$this->title = empty($model->name) ? Html::encode($model->user->username) : Html::encode($model->name);
$this->context->layout = 'space';
$this->params['profile'] = $model;
?>
<div class="row mt-30">
    <?= $this->render(
        '_nav.php', ['model' => $model]
    ) ?>
    <div class="col-md-10">
        <h2 class="h4">最近动态</h2>
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
    </div>
</div>