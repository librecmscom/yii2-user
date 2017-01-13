<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yuncms\user\widgets\Connect;

?>
<?php $form = ActiveForm::begin([
    'id' => 'login-modal',
    'enableAjaxValidation' => true,
    'enableClientValidation' => false,
    'validateOnBlur' => false,
    'validateOnType' => false,
    'validateOnChange' => false,
]); ?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title">
            <?= Yii::t('user', 'Sign in') ?>
        </h2>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
                <?= $form->field($model, 'login', ['inputOptions' => ['autofocus' => 'autofocus',]]) ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= Html::submitButton(Yii::t('user', 'Login'), ['class' => 'btn btn-primary btn-block  mt-10']) ?>
            </div>
            <div class="col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2 mt-10">
                <div><?= Yii::t('user', 'Quick login') ?></div>
                <div class="row">
                    <div class="col-md-12">
                        <?= Connect::widget([
                            'baseAuthUrl' => ['/user/security/auth'],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end(); ?>