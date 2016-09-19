<?php
use yii\helpers\Html;
use yii\helpers\Json;

/*
 * @var yii\web\View $this
 * @var yuncms\user\models\PortraitForm $model
 */

$this->title = Yii::t('user', 'My Portrait');
$this->registerJsFile('//open.web.meitu.com/sources/xiuxiu.js');
$postArgs = [
    Yii::$app->getRequest()->csrfParam => Yii::$app->getRequest()->getCsrfToken(),
    'PortraitForm' => ['portrait' => ''],
];
$this->registerJs('
        xiuxiu.embedSWF("altContent", 5, "100%", "420px");
        xiuxiu.setUploadArgs(' . Json::encode($postArgs) . ');
        xiuxiu.setUploadURL("' . Yii::$app->urlManager->createAbsoluteUrl('/user/settings/portrait') . '");
        xiuxiu.setUploadType(2);
        xiuxiu.setUploadDataFieldName("portrait");
        xiuxiu.onInit = function (){
            xiuxiu.loadPhoto("' . $model->profile->getAvatar(128) . '");
        }
        xiuxiu.onUploadResponse = function (data) {
            if(data == 200){
                window.location.reload();
            }
        }
');
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

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
                        <?= Html::img($model->profile->getAvatar(128)); ?>
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


