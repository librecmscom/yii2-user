<?php

use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var \yuncms\user\models\Profile $model
 */

$this->title = empty($model->name) ? Html::encode($model->user->username) : Html::encode($model->name);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="row">
            <div class="col-sm-6 col-md-4">

            </div>
            <div class="col-sm-6 col-md-8">
                <h4><?= $this->title ?></h4>
                <ul style="padding: 0; list-style: none outside none;">
                    <?php if (!empty($model->location)): ?>
                        <li>
                            <i class="glyphicon glyphicon-map-marker text-muted"></i> <?= Html::encode($model->location) ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($model->website)): ?>
                        <li>
                            <i class="glyphicon glyphicon-globe text-muted"></i> <?= Html::a(Html::encode($model->website), Html::encode($profile->website)) ?>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($model->public_email)): ?>
                        <li>
                            <i class="glyphicon glyphicon-envelope text-muted"></i> <?= Html::a(Html::encode($model->public_email), 'mailto:' . Html::encode($model->public_email)) ?>
                        </li>
                    <?php endif; ?>
                    <li>
                        <i class="glyphicon glyphicon-time text-muted"></i> <?= Yii::t('user', 'Joined on {0, date}', $model->user->created_at) ?>
                    </li>
                </ul>
                <?php if (!empty($model->bio)): ?>
                    <p><?= Html::encode($model->bio) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
