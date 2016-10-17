<?php
use yii\helpers\Html;
use yii\helpers\Json;
use yuncms\user\CropperAsset;

/*
 * @var yii\web\View $this
 * @var yuncms\user\models\PortraitForm $model
 */
CropperAsset::register($this);
$this->title = Yii::t('user', 'My Portrait');
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>


<div class="row">
    <div class="col-lg-6">
        <div class="img-container">
            <img id="image" src="/uploads/avatar/000/02/68/34_avatar_big.jpg?1856714839" alt="">        </div>
        <form id="w1" action="/user/avatar" method="post" enctype="multipart/form-data">
            <input type="hidden" name="_csrf" value="VDNqVnFidjNjfTkUOSkwURdwIyAGCRNDZXszbhJQOAsNWDA.KS4iXQ==">        <div class="form-group field-x">
                <input type="hidden" id="x" class="form-control" name="AvatarForm[x]"> <div class="help-block"></div>
            </div>        <div class="form-group field-y">
                <input type="hidden" id="y" class="form-control" name="AvatarForm[y]">
            </div>        <div class="form-group field-w">
                <input type="hidden" id="w" class="form-control" name="AvatarForm[w]">
            </div>        <div class="form-group field-h">
                <input type="hidden" id="h" class="form-control" name="AvatarForm[h]">
            </div>        <div class="docs-buttons">
                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="放大">
                    <span class="docs-tooltip" data-toggle="tooltip" title="放大">
                        <span class="fa fa-search-plus"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="缩小">
                    <span class="docs-tooltip" data-toggle="tooltip" title="缩小">
                        <span class="fa fa-search-minus"></span>
                    </span>
                    </button>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="左移">
                    <span class="docs-tooltip" data-toggle="tooltip" title="左移">
                        <span class="fa fa-arrow-left"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="右移">
                    <span class="docs-tooltip" data-toggle="tooltip" title="右移">
                        <span class="fa fa-arrow-right"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="上移">
                    <span class="docs-tooltip" data-toggle="tooltip" title="上移">
                        <span class="fa fa-arrow-up"></span>
                    </span>
                    </button>
                    <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="下移">
                    <span class="docs-tooltip" data-toggle="tooltip" title="下移">
                        <span class="fa fa-arrow-down"></span>
                    </span>
                    </button>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-primary" data-method="reset" title="重设">
                    <span class="docs-tooltip" data-toggle="tooltip" title="重设">
                        <span class="fa fa-refresh"></span>
                    </span>
                    </button>
                    <label class="btn btn-primary btn-upload" for="inputImage" title="上传头像">
                        <input type="hidden" name="AvatarForm[file]" value=""><input type="file" id="inputImage" class="sr-only" name="AvatarForm[file]" accept="image/*">                    <span class="docs-tooltip" data-toggle="tooltip" title="上传头像">
                        <span class="fa fa-upload"></span>
                    </span>
                    </label>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-check"></span></button>            </div>
            </div>
        </form>    </div>
    <div class="col-lg-6">
        <div class="docs-preview clearfix">
            <div class="img-preview preview-lg"></div>
            <div class="img-preview preview-md"></div>
            <div class="img-preview preview-sm"></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <?= $this->render('_header') ?>
        <div class="row">
            <div class="col-md-3">
                <dl class="pho-view">
                    <dt><?= Yii::t('user', 'My Portrait'); ?></dt>
                    <dd>
                        <?php //echo Html::img($model->profile->getAvatar(128)); ?>
                    </dd>
                </dl>
                <div class="field">
                    <?= Yii::t('user', 'From the local computer to choose a picture upload, picture size should not exceed 2 MB, long width not more than 3000 PX.'); ?>
                </div>
            </div>
            <div class="col-md-9">
                <div id="altContent"></div>
            </div>
        </div>
    </div>
</div>


