<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/*
 * @var yii\web\View $this
 * @var yuncms\user\models\Authentication $model
 */

$this->title = Yii::t('user', 'Authentication');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Authentication') ?></h2>
        <?php if (Yii::$app->user->identity->authentication): ?>
            <?php if (Yii::$app->user->identity->authentication->status == 0): ?>
                <div class="alert alert-info" role="alert">
                    <?= Yii::t('user', 'Your application is submitted successfully! We will be processed within three working days, the results will be processed by mail, station message to inform you, if in doubt please contact the official administrator.') ?>

                </div>
            <?php elseif (Yii::$app->user->identity->authentication->status == 1): ?>
                <div class="alert alert-danger" role="alert">
                    <?= Yii::t('user', 'Sorry, after passing your review, the information you submitted has not been approved. Please check the information and submit it again.') ?>
                    <?php if (Yii::$app->user->identity->authentication->failed_reason): ?>
                        <?= Yii::t('user', 'Failure reason:') ?><?= Yii::$app->user->identity->authentication->failed_reason ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <?php if (Yii::$app->user->identity->authentication): ?>
                    <div class="box box-solid">
                        <div class="box-body">
                            <dl class="dl-horizontal">
                                <dt><?= Yii::t('user', 'Real Name') ?></dt>
                                <dd><?= Yii::$app->user->identity->authentication->real_name ?></dd>
                                <dt><?= Yii::t('user', 'Email') ?></dt>
                                <dd><?= Yii::$app->user->identity->email ?></dd>
                                <dt><?= Yii::t('user', 'Id Card') ?></dt>
                                <dd><?= Yii::$app->user->identity->authentication->id_card ?></dd>
                                <dt><?= Yii::t('user', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= Yii::$app->user->identity->authentication->id_card_image ?>"/></dd>
                                <dd><a href="<?= Url::to(['/user/authentication/update']) ?>" class="btn btn-warning">修改认证资料</a>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <?php else: ?>
                    <?php $form = ActiveForm::begin([
                        'layout' => 'horizontal',
                        'options' => [
                            'enctype' => 'multipart/form-data',
                        ],
                    ]); ?>
                    <?= $form->field($model, 'real_name') ?>
                    <?= $form->field($model, 'id_card') ?>
                    <?= $form->field($model, 'imageFile')->fileInput(); ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
