<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yuncms\admin\widgets\Jarvis;

/* @var $this yii\web\View */
/* @var $model yuncms\user\models\Authentication */

$this->title = $model->user_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Manage Authentication'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 authentication-view">
            <?php Jarvis::begin([
                'noPadding' => true,
                'editbutton' => false,
                'deletebutton' => false,
                'header' => Html::encode($this->title),
                'bodyToolbarActions' => [
                    [
                        'label' => Yii::t('user', 'Manage Authentication'),
                        'url' => ['index'],
                    ],
                    [
                        'label' => Yii::t('user', 'Create Authentication'),
                        'url' => ['create'],
                    ],
                    [
                        'label' => Yii::t('user', 'Update Authentication'),
                        'url' => ['update', 'id' => $model->user_id],
                        'options' => ['class' => 'btn btn-primary btn-sm']
                    ],
                    [
                        'label' => Yii::t('user', 'Delete Authentication'),
                        'url' => ['delete', 'id' => $model->user_id],
                        'options' => [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                                'method' => 'post',
                            ],
                        ]
                    ],
                ]
            ]); ?>
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'user_id',
                    'user.username',
                    'real_name',
                    'id_card',
                    'idCardUrl:image',
                    'status',
                    'failed_reason',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>