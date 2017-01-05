<?php

use yii\grid\GridView;
?>
<div class="row">
    <div class="col-md-2">
        <?= $this->render('/setting/_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Credit Manage') ?></h2>
        <div class="row">
            <div class="col-md-12">
                <p class="mb-20">
                    <?= Yii::t('user', 'Your current credits are:') ?> <strong class="text-gold"><?= Yii::$app->user->identity->userData->credits ?></strong>
                </p>

                <?=GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => "{items}\n{pager}",
                    'columns' => [
                        'id',
                        'action',
                        'credits',
                        'source_subject',
                        'created_at:datetime'
                    ],
                ]);
                ?>
            </div>
        </div>
    </div>
</div>
