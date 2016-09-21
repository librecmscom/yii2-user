<?php

use yii\bootstrap\Nav;
use yii\web\View;
use yuncms\user\models\User;
/**
 * @var View $this
 * @var User $user
 * @var string $content
 */

$this->title = Yii::t('user', 'Update User Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="jarviswidget well" id="wid-id-3" data-widget-colorbutton="false" data-widget-editbutton="false"
     data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false"
     data-widget-custombutton="false" data-widget-sortable="false" role="widget">
    <div role="content">
        <!-- widget content -->
        <div class="widget-body no-padding">
            <div class="row">
                <div class="col-md-3">

                    <?= Nav::widget([
                        'options' => [
                            'class' => 'nav-pills nav-stacked',
                        ],
                        'items' => [
                            ['label' => Yii::t('user', 'Account details'), 'url' => ['/user/user/update', 'id' => $user->id]],
                            ['label' => Yii::t('user', 'Profile details'), 'url' => ['/user/user/update-profile', 'id' => $user->id]],
                            ['label' => Yii::t('user', 'Information'), 'url' => ['/user/user/view', 'id' => $user->id]],
                            ['label' => Yii::t('user', 'Education'), 'url' => ['/user/user/education', 'id' => $user->id]],
                            ['label' => Yii::t('user', 'Career'), 'url' => ['/user/user/career', 'id' => $user->id]],
                            '<hr>',
                            [
                                'label' => Yii::t('user', 'Confirm'),
                                'url' => ['/user/user/confirm', 'id' => $user->id],
                                'visible' => !$user->isConfirmed,
                                'linkOptions' => [
                                    'class' => 'text-success',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                                ],
                            ],
                            [
                                'label' => Yii::t('user', 'Block'),
                                'url' => ['/user/user/block', 'id' => $user->id],
                                'visible' => !$user->isBlocked,
                                'linkOptions' => [
                                    'class' => 'text-danger',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                ],
                            ],
                            [
                                'label' => Yii::t('user', 'Unblock'),
                                'url' => ['/user/user/block', 'id' => $user->id],
                                'visible' => $user->isBlocked,
                                'linkOptions' => [
                                    'class' => 'text-success',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                ],
                            ],
                            [
                                'label' => Yii::t('user', 'Delete'),
                                'url' => ['/user/user/delete', 'id' => $user->id],
                                'linkOptions' => [
                                    'class' => 'text-danger',
                                    'data-method' => 'post',
                                    'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                                ],
                            ],
                        ],
                    ]) ?>

                </div>
                <div class="col-md-9">
                    <?= $content ?>
                </div>
            </div>
        </div>
        <!-- end widget content -->
    </div>
    <!-- end widget div -->
</div>