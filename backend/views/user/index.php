<?php
use yii\web\View;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
use yuncms\user\backend\models\UserSearch;
use yuncms\user\models\Authentication;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */

$this->title = Yii::t('user', 'Manage Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('user', 'Manage User'),
                            'url' => ['/user/user/index'],
                        ],
                        [
                            'label' => Yii::t('user', 'Create User'),
                            'url' => ['/user/user/create'],
                        ],
                        [
                            'label' => Yii::t('user', 'Settings'),
                            'url' => ['/user/user/settings'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>
            <?= GridView::widget([
                'layout' => "{items}\n{summary}\n{pager}",
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'id',
                    'username',
                    'email:email',
                    'mobile',
                    //'extend.amount',
                    //'extend.point',
//                    [
//                        'header' => Yii::t('user', 'Authentication'),
//                        'value' => function ($model) {
//                            if (\yuncms\authentication\models\Authentication::isAuthentication($model->id)) {
//                                if ($model->authentication->status == \yuncms\authentication\models\Authentication::STATUS_PENDING) {
//                                    return Yii::t('user', 'Pending review');
//                                } elseif ($model->authentication->status == \yuncms\authentication\models\Authentication::STATUS_REJECTED) {
//                                    return Yii::t('user', 'Rejected');
//                                } elseif ($model->authentication->status == \yuncms\authentication\models\Authentication::STATUS_AUTHENTICATED) {
//                                    return Yii::t('user', 'Authenticated');
//                                }
//                            }
//                            return Yii::t('user', 'UnSubmitted');
//                        },
//                        'format' => 'raw',
//                    ],
                    [
                        'attribute' => 'registration_ip',
                        'value' => function ($model) {
                            return $model->registration_ip == null
                                ? '<span class="not-set">' . Yii::t('app', '(not set)') . '</span>'
                                : $model->registration_ip;
                        },
                        'format' => 'html',
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'datetime',
                        'filter' => \yii\jui\DatePicker::widget([
                            'model' => $searchModel,
                            'options' => [
                                'class' => 'form-control'
                            ],
                            'attribute' => 'created_at',
                            'name' => 'created_at',
                            'dateFormat' => 'yyyy-MM-dd'
                        ]),
                    ],
                    [
                        'header' => Yii::t('user', 'Confirmation'),
                        'value' => function ($model) {
                            if ($model->isEmailConfirmed) {
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
                        'visible' => Yii::$app->settings->get('enableConfirmation', 'user'),
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

                    ],
                ]
            ]); ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>
