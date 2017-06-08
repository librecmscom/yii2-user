<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\widgets\ListView;
use yuncms\question\widgets\Tags;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('user', 'People');
?>
<div class="row">
    <div class="col-xs-12 col-md-9 main">
        <section class="tag-header mt-20">
            <div>
                <span class="h4 tag-header-title"><?= Html::encode($model->name) ?></span>
                <div class="tag-header-follow">
                    <?php if (!Yii::$app->user->isGuest && Yii::$app->user->identity->hasTagValues($model->id)): ?>
                        <button type="button" data-target="follow-tag" class="btn btn-default btn-xs active"
                                data-source_id="<?= $model->id ?>" data-show_num="false" data-toggle="tooltip"
                                data-placement="right" title="" data-original-title="关注后将获得更新提醒">已关注
                        </button>
                    <?php else: ?>
                        <button type="button" data-target="follow-tag" class="btn btn-default btn-xs"
                                data-source_id="<?= $model->id ?>" data-show_num="false" data-toggle="tooltip"
                                data-placement="right" title="" data-original-title="关注后将获得更新提醒">关注
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <p class="tag-header-summary">
                <?= empty($model->description) ? Yii::t('user', 'No introduction') : Html::encode($model->description); ?>
            </p>
        </section>
        <?= Nav::widget([
            'options' => ['class' => 'nav nav-tabs nav-tabs-zen mb10'],
            'items' => [
                ['label' => Yii::t('user', 'Code'), 'url' => ['/code/code/tag', 'tag' => Html::encode($model->name)]],
                ['label' => Yii::t('user', 'Question'), 'url' => ['/question/question/tag', 'tag' => Html::encode($model->name)]],
                ['label' => Yii::t('user', 'User'), 'url' => ['/user/people/tag', 'tag' => Html::encode($model->name)]],
            ]
        ]); ?>
        <div class="tab-content">
            <?=ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => '_item',//子视图
                'options' => ['class' => 'user-list'],
                'itemOptions'=>['class'=>'media'],

            ]);
            ?>
            <!-- /.stream-list -->
        </div>
    </div><!-- /.main -->
    <div class="col-xs-12 col-md-3 side">
        <?= Tags::widget() ?>
    </div>
</div>