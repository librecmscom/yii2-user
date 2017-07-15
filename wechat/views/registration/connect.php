<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\BootstrapAsset;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var yuncms\user\models\User $model
 * @var yuncms\user\models\Social $account
 */
BootstrapAsset::register($this);
$this->title = Yii::t('user', 'Sign in');
//$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
]); ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'password', ['inputOptions' => ['autocomplete' => 'off']])->passwordInput() ?>
<?= Html::submitButton(Yii::t('user', 'Register && Continue'), ['class' => 'btn btn-success btn-block']) ?>
<?php ActiveForm::end(); ?>
