<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $user
 * @var yuncms\user\models\Profile $profile
 */

?>
<?php $this->beginContent('@yuncms/user/backend/views/user/update.php', ['model' => $model]) ?>

<?php $form = ActiveForm::begin(['layout' => 'horizontal']); ?>
<fieldset>
    <?= $form->field($profile, 'nickname') ?>
    <?= $form->field($profile, 'public_email') ?>
    <?= $form->field($profile, 'website') ?>
    <?= $form->field($profile, 'location') ?>
    <?= $form->field($profile, 'bio')->textarea() ?>
</fieldset>

<div class="form-actions">
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
