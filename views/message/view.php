<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Message Inbox');
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="row">
    <div class="col-md-3">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-9" style="margin-bottom:30px">
        <h1 class="msg-to-title">
       	<span class="pull-left">
			<?= Yii::t('app', 'Dialogue with'); ?>

            <?php
            if (Yii::$app->user->getId() == $conversation->user_id) {//收件人是自己
                $conversation->updateAttributes(['status' => 2]);
                ?>


                <a class="tiltle-username" href="<?=Url::to(['/user/profile/show', 'id' => $conversation->from_id]);?>"><?=Html::img($conversation->from->getAvatar(48),['class'=>'top-title-pho']);?><?=$conversation->from->username;?></a>

                <?php
            } else {
                ?>
                <a class="tiltle-username" href="<?=Url::to(['/user/profile/show', 'id' => $conversation->user_id]);?>"><?=Html::img($conversation->user->getAvatar(48),['class'=>'top-title-pho']);?><?=$conversation->user->username;?></a>

            <?php } ?>

           </span>
            <a class="pull-right" style=" font-size:15px" href="<?= Url::to(['/user/message/index']); ?>"><span class="fa fa-backward"></span><?= Yii::t('app', 'Back to list'); ?></a>
            <div style="clear:both"></div>
        </h1>
        <div class="mg-con">
            <div class="message-main">
                <div class="media-left">
                    <a href="<?=Url::to(['/user/profile/show', 'id' => $conversation->from_id]);?>" rel="author">
                        <?=Html::img($conversation->from->getAvatar(48),['class'=>'media-object']);?>
                    </a>
                </div>
                <div class="media-body">
                    <div class="media-heading p-m-username">
                        <a class="p-m-u" href="<?=Url::to(['/user/profile/show', 'id' => $conversation->from_id]);?>"><?=$conversation->from->username;?></a>
                        <span class="msg-time"> <?=Yii::$app->formatter->asRelativeTime($conversation->created_at);?></span>
                    </div>
                    <?=$conversation->message;?>
                </div>
            </div>
            <?php
            echo ListView::widget([
                'dataProvider' => $dataProvider,
                'itemOptions' => ['tag' => 'li', 'class' => 'media'],
                'itemView' => '_view_item',//子视图
                'layout' => "{items}\n{pager}",
                'options' => [
                    'tag' => 'ul',
                    'class' => 'media-list dialogue-list'
                ]
            ]); ?>
        </div>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'id' => 'message-form',
            'options' => ['class' => 'msg-form-view'],
            'fieldConfig' => [
                'template' => "{input}{error}\n{hint}",
            ],
        ]); ?>

        <?= $form->field($model, 'parent',['template'=>'{input}'])->hiddenInput() ?>

        <?= $form->field($model, 'message')->textarea(['onkeydown'=>'keySend(event)']) ?>
        <div class="form-group form-bottom-bar">
            <span class="send-tip stp1"><?= Yii::t('app', 'Press CTRL+ENTER to send messages'); ?></span>
            <span class="send-tip stp2"><?= Yii::t('app', 'Click the SEND button to send the message'); ?></span>
            <button type="reset" class="btn btn-primary resetbtn"><?= Yii::t('app', 'Reset'); ?></button>
            <?= \yii\helpers\Html::submitButton('<span class="fa fa-send"></span>'.Yii::t('app', 'Send') , ['id' =>'sendBtn','class' => 'btn btn-primary']) ?>
        </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
</div>
