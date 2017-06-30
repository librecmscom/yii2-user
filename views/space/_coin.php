<?php
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * @var \yuncms\user\models\Coin $model
 */
?>

<div class="pull-left">
    <span class="badge"><?= intval($model->coins) ?></span>
</div>
<p>
        <span class="text-muted">
            <?php if (empty($model->actionText)) {
                switch ($model->action) {
                    case 'test':
                        echo Yii::t('app', 'Concerned about live');
                        break;
                    default:
                        echo $model->action;
                        break;
                }
            } else {
                echo $model->actionText;
            } ?> · <?= Yii::$app->formatter->asRelativeTime($model->created_at); ?></span>
    <?php if (in_array($model->action, ['ask', 'answer', 'answer_adopted'])): ?>
        <a class="ml-5" target="_blank"
           href="<?= Url::to(['/question/question/view', 'id' => $model->source_id]) ?>"><?= Html::encode($model->source_subject) ?></a>
    <?php elseif (in_array($model->action, ['create_article'])): ?>
        <a class="ml-5" target="_blank"
           href="<?= Url::to(['/article/article/view', 'id' => $model->source_id]) ?>"><?= Html::encode($model->source_subject) ?></a>
    <?php endif; ?>
</p>

