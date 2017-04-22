<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use xutl\inspinia\Box;
use xutl\inspinia\Toolbar;
use xutl\inspinia\Alert;
/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $model
 */

$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
            <?= Alert::widget() ?>
            <?php Box::begin([
                    'header' => Html::encode($this->title),
            ]); ?>
            <div class="row">
                <div class="col-sm-4 m-b-xs">
                    <?= Toolbar::widget(['items' => [
                        [
                            'label' => Yii::t('user', 'Manage Users'),
                            'url' => ['/user/user/index'],
                        ],
                        [
                            'label' => Yii::t('user', 'Create User'),
                            'url' => ['/user/user/create'],
                        ],
                    ]]); ?>
                </div>
                <div class="col-sm-8 m-b-xs">

                </div>
            </div>

            <div class="alert alert-info">
                <?= Yii::t('user', 'Credentials will be sent to the user by email') ?>.
                <?= Yii::t('user', 'A password will be generated automatically if not provided') ?>.
            </div>
            <?= $this->render('_form', ['model' => $model]) ?>
            <?php Box::end(); ?>
        </div>
    </div>
</div>