<?php
/**
 * @var yii\web\View
 * @var yuncms\user\models\User
 */
?>

<?php $this->beginContent('@vendor/yuncms/yii2-user/backend/views/user/update.php', ['model' => $model]) ?>

<table class="table">
    <tr>
        <td><strong><?= Yii::t('user', 'Registration time') ?>:</strong></td>
        <td><?= Yii::t('user', '{0, date, MMMM dd, YYYY HH:mm}', [$model->created_at]) ?></td>
    </tr>
    <?php if ($model->registration_ip !== null): ?>
        <tr>
            <td><strong><?= Yii::t('user', 'Registration IP') ?>:</strong></td>
            <td><?= $model->registration_ip ?></td>
        </tr>
    <?php endif ?>
    <tr>
        <td><strong><?= Yii::t('user', 'Email Confirmation status') ?>:</strong></td>
        <?php if ($model->isEmailConfirmed): ?>
            <td class="text-success"><?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$model->email_confirmed_at]) ?></td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('user', 'Mobile Confirmation status') ?>:</strong></td>
        <?php if ($model->isMobileConfirmed): ?>
            <td class="text-success"><?= Yii::t('user', 'Confirmed at {0, date, MMMM dd, YYYY HH:mm}', [$model->mobile_confirmed_at]) ?></td>
        <?php else: ?>
            <td class="text-danger"><?= Yii::t('user', 'Unconfirmed') ?></td>
        <?php endif ?>
    </tr>
    <tr>
        <td><strong><?= Yii::t('user', 'Block status') ?>:</strong></td>
        <?php if ($model->isBlocked): ?>
            <td class="text-danger"><?= Yii::t('user', 'Blocked at {0, date, MMMM dd, YYYY HH:mm}', [$model->blocked_at]) ?></td>
        <?php else: ?>
            <td class="text-success"><?= Yii::t('user', 'Not blocked') ?></td>
        <?php endif ?>
    </tr>
</table>

<?php $this->endContent() ?>
