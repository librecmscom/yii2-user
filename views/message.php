<?php
/**
 * @var \yii\web\View $this
 * @var \yuncms\user\Module $module
 */

$this->title = $title;

?>

<?= $this->render('/_alert', [
    'module' => $module,
]) ?>
