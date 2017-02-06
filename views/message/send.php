<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\bootstrap\Nav;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Message Inbox');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('/_right_menu') ?>
    </div>
    <div class="col-md-9" style="margin-bottom:30px">
        <div class="page-header" style="margin-bottom:10px">
            <?= Nav::widget([
                'options' => ['class' => 'nav nav-tabs nav-main'],
                'items' => [
                    //站内通知
                    ['label' => '<span class="fa fa-bell head-sec-ico"></span><span class="h-s-title">'.Yii::t('user', 'Site Notice').'</span>', 'url' => ['/notice'],'encode'=>false],
                    //私信
                    ['label' => '<span class="fa fa-comments head-sec-ico"></span><span class="h-s-title">'.Yii::t('user', 'My message').'</span>', 'url' => ['/user/message/index'],'encode'=>false],
                ]
            ]); ?>
        </div>
        <h1 style="display:block; line-height: 40px; border-bottom: 1px dotted #ddd;"><?= Yii::t('user', 'Send Message'); ?> <a class="btn btn-primary pull-right" style="padding:6px 12px; font-size:15px" href="<?= Url::to(['/user/message/index']); ?>"><span class="fa fa-backward"></span><?= Yii::t('user', 'Back to list'); ?></a></h1>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'message-form',
        ]); ?>
        <?= $form->field($model, 'username')->label(Yii::t('user','Username'))->input('', ['placeholder' => Yii::t('user','Please enter a user name')]); ?>
        <?= $form->field($model, 'message')->label(Yii::t('app','Message content'))->textarea(['placeholder' => Yii::t('user','Please enter the message content')]) ?>
        <div class="form-group">
            <?= \yii\helpers\Html::submitButton(Yii::t('app','Send'), ['class' => 'btn btn-block btn-primary']) ?>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
