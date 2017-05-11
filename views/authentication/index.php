<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\captcha\Captcha;
use yii\bootstrap\ActiveForm;
use xutl\bootstrap\filestyle\FilestyleAsset;
use yuncms\user\models\Authentication;

FilestyleAsset::register($this);
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
                                <dt><?= Yii::t('user', 'Full Name') ?></dt>
                                <dd><?= Yii::$app->user->identity->authentication->real_name ?></dd>
                                <dt><?= Yii::t('user', 'Email') ?></dt>
                                <dd><?= Yii::$app->user->identity->email ?></dd>
                                <dt><?= Yii::t('user', 'Id Type') ?></dt>
                                <dd><?= Yii::$app->user->identity->authentication->type ?></dd>
                                <dt><?= Yii::t('user', 'Id Card') ?></dt>
                                <dd><?= Yii::$app->user->identity->authentication->id_card ?></dd>
                                <dt><?= Yii::t('user', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= Yii::$app->user->identity->authentication->passport_cover ?>"/></dd>
                                <dt><?= Yii::t('user', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= Yii::$app->user->identity->authentication->passport_person_page ?>"/></dd>
                                <dt><?= Yii::t('user', 'Id Card Image') ?></dt>
                                <dd><img class="img-responsive"
                                         src="<?= Yii::$app->user->identity->authentication->passport_self_holding ?>"/></dd>
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

                    <?= $form->field($model, 'id_type')->dropDownList([
                        Authentication::TYPE_ID => Yii::t('user', 'ID Card'),
                        Authentication::TYPE_PASSPORT => Yii::t('user', 'Passport ID'),
                        Authentication::TYPE_ARMYID => Yii::t('user', 'Army ID'),
                        Authentication::TYPE_TAIWANID => Yii::t('user', 'Taiwan ID'),
                        Authentication::TYPE_HKMCID => Yii::t('user', 'HKMC ID'),
                    ]); ?>
                    <?= $form->field($model, 'id_card') ?>
                    <?= $form->field($model, 'id_file')->fileInput(['class' => 'filestyle', 'data' => [
                        'buttonText' => Yii::t('user', 'Choose file')
                    ]]); ?>
                    <?= $form->field($model, 'id_file1')->fileInput(['class' => 'filestyle', 'data' => [
                        'buttonText' => Yii::t('user', 'Choose file')
                    ]]); ?>
                    <?= $form->field($model, 'id_file2')->fileInput(['class' => 'filestyle', 'data' => [
                        'buttonText' => Yii::t('user', 'Choose file')
                    ]]); ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'captchaAction' => '/user/authentication/captcha',

                    ]); ?>

                    <?= $form->field($model, 'registrationPolicy')->checkbox()->label(
                        Yii::t('user', 'Agree and accept {serviceAgreement} and {privacyPolicy}', [
                            'serviceAgreement' => Html::a(Yii::t('user', 'Service Agreement'), ['/legal/terms']),
                            'privacyPolicy' => Html::a(Yii::t('user', 'Privacy Policy'), ['/legal/privacy']),
                        ]), [
                            'encode' => false
                        ]
                    ) ?>

                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('user', 'Submit'), ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
