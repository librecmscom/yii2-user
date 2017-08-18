<?php
use yii\helpers\Html;
use yuncms\user\frontend\widgets\Connect;

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 */

$this->title = Yii::t('user', 'Social Networks');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-2">
        <?= $this->render('/_profile_menu') ?>
    </div>
    <div class="col-md-10">
        <h2 class="h3 profile-title"><?= Yii::t('user', 'Social Networks') ?></h2>
        <div class="row">
            <div class="alert alert-info">
                <p><?= Yii::t('user', 'You can connect multiple accounts to be able to log in using them') ?>.</p>
            </div>
            <div class="col-md-12">

                <?php $auth = Connect::begin([
                    'baseAuthUrl' => ['/user/security/auth'],
                    'accounts' => $user->accounts,
                    'autoRender' => false,
                    'popupMode' => false,
                ]) ?>


                <table class="table">
                    <?php foreach ($auth->getClients() as $client): ?>
                        <tr>
                            <td style="width: 32px; vertical-align: middle">
                                <?= Html::tag('span', '', ['class' => 'auth-icon ' . $client->getName()]) ?>
                            </td>
                            <td style="vertical-align: middle">
                                <strong><?= $client->getTitle() ?></strong>
                            </td>
                            <td style="width: 120px">
                                <?= $auth->isConnected($client) ?
                                    Html::a(Yii::t('user', 'Disconnect'), $auth->createClientUrl($client), [
                                        'class' => 'btn btn-danger btn-block',
                                        'data-method' => 'post',
                                    ]) :
                                    Html::a(Yii::t('user', 'Connect'), $auth->createClientUrl($client), [
                                        'class' => 'btn btn-success btn-block',
                                    ])
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <?php Connect::end() ?>
            </div>
        </div>
    </div>
</div>