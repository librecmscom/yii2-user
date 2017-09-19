<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="users-search">
    <?=Html::beginForm(['index'], 'get', ['class' => 'form-inline'])?>
    <div class="input-group">
        <?= Html::input('text', 'q', null, [
            'class' => 'form-control search-input',
            'placeholder' => Yii::t('user', 'Search for people you are interested in'),
            'autocomplete' => 'off',
            'x-webkit-speech' => true,
            'x-webkit-grammar' => 'builtin:translate',
            'value' => Yii::$app->request->getQueryParam('q')
        ]) ?>
        <span class="input-group-btn">
            <button type="submit" class="btn btn-info search-btn search-btn-danger"><?= Yii::t('app', 'Search'); ?></button>
        </span>
    </div>
    <?=Html::endForm()?>
</div>