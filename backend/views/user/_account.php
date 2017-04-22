<?php

use yii\helpers\Html;
use xutl\inspinia\ActiveForm;


/*
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 */

?>

<?php $this->beginContent('@yuncms/user/backend/views/user/update.php', ['model' => $model]) ?>

<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
]); ?>

<?= $this->render('_form', ['form' => $form, 'model' => $model]) ?>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
