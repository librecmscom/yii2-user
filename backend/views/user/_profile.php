<?php
use xutl\inspinia\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 * @var yuncms\user\models\Profile $profile
 */

?>
<?php $this->beginContent('@yuncms/user/backend/views/user/update.php', ['model' => $model]) ?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>

<?= $form->field($profile, 'email') ?>
<div class="hr-line-dashed"></div>
<?= $form->field($profile, 'website') ?>
<div class="hr-line-dashed"></div>
<?= $form->field($profile, 'location') ?>
<div class="hr-line-dashed"></div>
<?= $form->field($profile, 'bio')->textarea() ?>
<div class="hr-line-dashed"></div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-2">
        <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
