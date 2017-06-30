<?php

use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <h2 class="panel-title"><?= Yii::t('user', 'Active member'); ?></h2>
    </div>
    <div class="panel-body">
        <ul class="avatar-list">
            <?php if (!empty($models)): ?>
                <?php foreach ($models as $model): ?>
                    <li><a href="<?= Url::to(['/user/space/view', 'id' => $model->id]); ?>" rel="author">
                            <img src="<?= $model->getAvatar(); ?>" class="avatar-24" alt="<?= Html::encode($model->username); ?>">
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <?= Yii::t('user', 'No popular users') ?>
            <?php endif; ?>
        </ul>
    </div>
</div>
