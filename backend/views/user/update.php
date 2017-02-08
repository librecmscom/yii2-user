<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\web\View;
use yuncms\user\models\User;
use yuncms\admin\widgets\Jarvis;

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
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php Jarvis::begin([
                'noPadding' => $this->params['noPadding'],
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
                    ['label' => Yii::t('user', 'Account details'), 'url' => ['/user/user/update', 'id' => $model->id]],
                    ['label' => Yii::t('user', 'Profile details'), 'url' => ['/user/user/update-profile', 'id' => $model->id]],
                    ['label' => Yii::t('user', 'Information'), 'url' => ['/user/user/view', 'id' => $model->id]],
                    ['label' => Yii::t('user', 'Education'), 'url' => ['/user/user/education', 'id' => $model->id]],
                    ['label' => Yii::t('user', 'Career'), 'url' => ['/user/user/career', 'id' => $model->id]],
                    [
                        'label' => Yii::t('user', 'Confirm'),
                        'url' => ['/user/user/confirm', 'id' => $model->id],
                        'visible' => !$model->isConfirmed,
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
                ]
            ]); ?>
            <?= $content ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>