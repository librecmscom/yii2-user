<?php

use yii\bootstrap\Nav;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\widgets\Jarvis;
/**
 * @var yii\web\View $this
 * @var yuncms\user\models\User $model
 */

$this->title = Yii::t('user', 'Create a user account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('user', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<section id="widget-grid">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <?php Jarvis::begin(['editbutton' => false, 'deletebutton' => false, 'header' => Html::encode($this->title),
                'bodyToolbarActions' => [
                    [
                        'label' => Yii::t('user', 'Manage User'),
                        'url' => ['/user/user/index'],
                    ],
                    [
                        'label' => Yii::t('user', 'Create User'),
                        'url' => ['/user/user/create'],
                    ],
                ]
            ]); ?>
            <div class="alert alert-info">
                <?= Yii::t('user', 'Credentials will be sent to the user by email') ?>.
                <?= Yii::t('user', 'A password will be generated automatically if not provided') ?>.
            </div>
            <?= $this->render('_form', ['model' => $model]) ?>
            <?php Jarvis::end(); ?>
        </article>
    </div>
</section>