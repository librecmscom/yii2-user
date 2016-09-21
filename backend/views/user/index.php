<?php
use yii\web\View;
use yii\helpers\Html;
use backend\components\GridView;
use yii\jui\DatePicker;
use yii\data\ActiveDataProvider;
use yuncms\user\backend\models\UserSearch;
use backend\widgets\Jarvis;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php Jarvis::begin([
                'noPadding' => true,
                'editbutton' => false,
                'deletebutton' => false,
                'header' => Html::encode($this->title),
                'bodyToolbarActions' => [
                    [
                        'label' => Yii::t('user', 'Manage User'),
                        'url' => ['/user/user/index'],
                    ],
                    [
                        'label' => Yii::t('user', 'Create User'),
                        'url' => ['/user/user/create'],
                    ],
                ]
            ]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    'id',
                    'username',
                    'email:email',
                    [
                        'attribute' => 'registration_ip',
                        'value' => function ($model) {
                            return $model->registration_ip == null
                                ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>'
                                : $model->registration_ip;
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => function ($model) {
                            if (extension_loaded('intl')) {
                                return Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]);
                            } else {
                                return date('Y-m-d H:i:s', $model->created_at);
                            }
                        },
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'dateFormat' => 'php:Y-m-d',
                            'options' => [
                                'class' => 'form-control',
                            ],
                        ]),
                    ],
                    [
                        'header' => Yii::t('user', 'Confirmation'),
                        'value' => function ($model) {
                            if ($model->isConfirmed) {
                                return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
                            } else {
                                return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-success btn-block',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                                ]);
                            }
                        },
                        'format' => 'raw',
                        'visible' => Yii::$app->getModule('user')->enableConfirmation,
                    ],
                    [
                        'header' => Yii::t('user', 'Block status'),
                        'value' => function ($model) {
                            if ($model->isBlocked) {
                                return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-success btn-block',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                ]);
                            } else {
                                return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                                    'class' => 'btn btn-xs btn-danger btn-block',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                ]);
                            }
                        },
                        'format' => 'raw',
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => '{update} {delete}',
                    ],
                ]
            ]); ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>
