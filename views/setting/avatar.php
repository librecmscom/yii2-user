<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;
use xutl\fontawesome\Asset;
use yuncms\user\CropperAsset;

/*
 * @var yii\web\View $this
 * @var yuncms\user\models\PortraitForm $model
 */
Asset::register($this);
CropperAsset::register($this);
$this->title = Yii::t('user', 'My Avatar');
?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="row">
            <div class="col-lg-6">
                <div class="img-container">
                    <?= Html::img(Yii::$app->user->identity->getAvatar('big'), ['id' => 'image', 'alt' => Yii::$app->user->identity->username]); ?>
                </div>
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data',
                    ],
                ]); ?>
                <?= $form->field($model, 'x')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'y')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'width')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'height')->hiddenInput()->label(false) ?>
                <div class="docs-buttons">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="reset"
                                title="<?= Yii::t('user', 'Reset'); ?>">
                            <span class="docs-tooltip" data-toggle="tooltip" title="<?= Yii::t('user', 'Reset'); ?>">
                                <span class="fa fa-refresh"></span>
                            </span>
                        </button>
                        <label class="btn btn-primary btn-upload" for="inputImage"
                               title="<?= Yii::t('user', 'Upload avatar'); ?>">
                            <?= $form->field($model, 'file', [
                                'options' => [
                                    'tag' => false
                                ],
                                'inputOptions' => [
                                    'class' => 'sr-only',
                                    'id' => 'inputImage',
                                    'accept' => 'image/*'
                                ],
                            ])->fileInput()->label(false)->error(false); ?>
                            <span class="docs-tooltip" data-toggle="tooltip"
                                  title="<?= Yii::t('user', 'Upload avatar'); ?>">
                                <span class="fa fa-upload"></span>
                            </span>
                        </label>
                        <?= Html::submitButton('<span class="fa fa-check"></span>', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col-lg-6">
                <div class="docs-preview clearfix">
                    <div class="img-preview preview-lg"></div>
                    <div class="img-preview preview-md"></div>
                    <div class="img-preview preview-sm"></div>
                </div>
            </div>
        </div>
    </div>
</div>


