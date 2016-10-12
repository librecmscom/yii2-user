<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yuncms\user\helpers\Timezone;

/*
 * @var yii\web\View $this
 * @var yii\bootstrap\ActiveForm $form
 * @var yuncms\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <?= $this->render('_header') ?>
        <?php $form = ActiveForm::begin([
            'id' => 'profile-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-6\">{error}\n{hint}</div>",
                'labelOptions' => ['class' => 'col-lg-3 control-label'],
            ],
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'validateOnBlur' => false,
        ]); ?>

        <?= $form->field($model, 'nickname') ?>

        <?= $form->field($model, 'public_email') ?>

        <?= $form->field($model, 'sex')->inline(true)->radioList(['0' => Yii::t('user', 'Unconfirmed'),'1' => Yii::t('user', 'Male'), '2' => Yii::t('user', 'Female')], [
            'template' => "{label}\n<div class=\"col-lg-6\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-6\">{error}\n{hint}</div>",
        ]); ?>

        <?= $form->field($model, 'address') ?>

        <?= $form->field($model, 'website') ?>

        <?php
        // by default, this contains the entire php timezone list of 400+ entries
        // so you may want to set up a fancy jquery select plugin for this, eg, select2 or chosen
        // alternatively, you could use your own filtered list
        // a good example is twitter's timezone choices, which contains ~143  entries
        // @link https://twitter.com/settings/account
        ?>
        <?= $form->field($model, 'timezone')->dropDownList(ArrayHelper::map(Timezone::getAll(), 'identifier', 'name')); ?>

        <?= $form->field($model, 'location') ?>

        <?= $form->field($model, 'introduction')->textarea() ?>

        <?= $form->field($model, 'bio')->textarea() ?>

        <div class="form-group">
            <div class="col-lg-offset-3 col-lg-6">
                <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-success']) ?>
                <br>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
