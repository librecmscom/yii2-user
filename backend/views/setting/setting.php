<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

use backend\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\Jarvis;

$this->title = Yii::t('user', 'User Setting');
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php Jarvis::begin([
                'editbutton' => false,
                'deletebutton' => false,
                'header' => Html::encode($this->title),
            ]); ?>
            <?php $form = ActiveForm::begin(['layout' => 'horizontal',]); ?>
            <fieldset>
                <?= $form->field($model, 'mailSender', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-2',
                    ],
                ]); ?>
                <?= $form->field($model, 'mailViewPath', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-4',
                    ],
                ]); ?>
                <?= $form->field($model, 'enableRegistration')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether to enable registration.'));
                ?>
                <?= $form->field($model, 'enableRegistrationCaptcha')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether to enable registration captcha.'));
                ?>
                <?= $form->field($model, 'enableGeneratingPassword')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether to remove password field from registration form.'));
                ?>
                <?= $form->field($model, 'enableConfirmation')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether user has to confirm his account.'));
                ?>
                <?= $form->field($model, 'enableUnconfirmedLogin')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether to allow logging in without confirmation.'));
                ?>
                <?= $form->field($model, 'enablePasswordRecovery')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether to enable password recovery.'));
                ?>
                <?= $form->field($model, 'enableAccountDelete')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Whether user can remove his account.'));
                ?>
                <?= $form->field($model, 'emailChangeStrategy')
                    ->inline(true)
                    ->radioList(['1' => Yii::t('app', 'Yes'), '0' => Yii::t('app', 'No'), '2' => Yii::t('app', 'No')])
                    ->hint(Yii::t('user', 'Email changing strategy.'));
                ?>
                <?= $form->field($model, 'rememberFor', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-2',
                    ],
                    'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">s</span></div>',
                ])
                    ->hint(Yii::t('user', 'The time you want the user will be remembered without asking for credentials.'));
                ?>
                <?= $form->field($model, 'confirmWithin', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-2',
                    ],
                    'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">s</span></div>',
                ])
                    ->hint(Yii::t('user', 'The time before a confirmation token becomes invalid.'));
                ?>
                <?= $form->field($model, 'recoverWithin', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-2',
                    ],
                    'inputTemplate' => '<div class="input-group">{input}<span class="input-group-addon">s</span></div>',
                ])
                    ->hint(Yii::t('user', 'The time before a recovery token becomes invalid.'));
                ?>
                <?= $form->field($model, 'cost', [
                    'horizontalCssClasses' => [
                        'wrapper' => 'col-sm-2',
                    ],
                ])
                    ->hint(Yii::t('user', 'Cost parameter used by the Blowfish hash algorithm.'));
                ?>
            </fieldset>
            <div class="form-actions">
                <div class="row">
                    <div class="col-md-12">
                        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>