<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\web\View;
use yuncms\user\models\User;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;

/**
 * @var View $this
 * @var User $model
 * @var string $content
 */

$this->title = Yii::t('user', 'Update User Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
if (!isset($this->params['noPadding'])) {
    $this->params['noPadding'] = null;
}

?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                'noPadding' => $this->params['noPadding'],
                'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-6 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('user', 'Manage User'),
                            'url' => ['/user/user/index'],
                        ],
                        [
                            'label' => Yii::t('user', 'Create User'),
                            'url' => ['/user/user/create'],
                        ],
                        ['label' => Yii::t('user', 'Account details'), 'url' => ['/user/user/update', 'id' => $model->id]],
                        ['label' => Yii::t('user', 'Profile details'), 'url' => ['/user/user/update-profile', 'id' => $model->id]],
                        ['label' => Yii::t('user', 'Information'), 'url' => ['/user/user/view', 'id' => $model->id]],
                        ['label' => Yii::t('user', 'Education'), 'url' => ['/user/user/education', 'id' => $model->id]],
                        ['label' => Yii::t('user', 'Career'), 'url' => ['/user/user/career', 'id' => $model->id]],
                        [
                            'label' => Yii::t('user', 'Email Confirm'),
                            'url' => ['/user/user/confirm', 'id' => $model->id],
                            'visible' => !$model->isEmailConfirmed,
                            'options' => [
                                'class'=>'btn btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                            ],
                        ],
                        [
                            'label' => Yii::t('user', 'Mobile Confirm'),
                            'url' => ['/user/user/confirm', 'id' => $model->id],
                            'visible' => !$model->isMobileConfirmed,
                            'options' => [
                                'class'=>'btn btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                            ],
                        ],
                        [
                            'label' => Yii::t('user', 'Block'),
                            'url' => ['/user/user/block', 'id' => $model->id],
                            'visible' => !$model->isBlocked,
                            'options' => [
                                'class'=>'btn btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                            ],
                        ],
                        [
                            'label' => Yii::t('user', 'Unblock'),
                            'url' => ['/user/user/block', 'id' => $model->id],
                            'visible' => $model->isBlocked,
                            'options' => [
                                'class'=>'btn btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                            ],
                        ],
                        [
                            'label' => Yii::t('user', 'Delete'),
                            'url' => ['/user/user/delete', 'id' => $model->id],
                            'options' => [
                                'class'=>'btn btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to delete this user?'),
                            ],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-6 m-b-xs">

                </div>
            </div>
            <?= $content ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>