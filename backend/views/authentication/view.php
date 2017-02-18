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
                        'label' => Yii::t('user', 'Update Authentication'),
                        'url' => ['update', 'id' => $model->user_id],
                        'options' => ['class' => 'btn btn-primary btn-sm']
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
                    [
                        'header' => Yii::t('user', 'Authentication'),
                        'attribute' => 'status',
                        'value' => function ($model) {
                            if ($model->status == 0) {
                                return Yii::t('user', 'Pending review');
                            } elseif ($model->status == 1) {
                                return Yii::t('user', 'Rejected');
                            } elseif ($model->status == 2) {
                                return Yii::t('user', 'Authenticated');
                            }
                        },
                        'format' => 'raw',
                    ],
                    'failed_reason',
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>