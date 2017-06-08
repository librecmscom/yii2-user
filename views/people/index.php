<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\LinkSorter;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('user', 'People');
?>
    <div class="row">
        <div class="col-xs-12 col-sm-7 col-md-8 col-lg-9">
            <h1 class="page-title txt-color-blueDark"><i class="fa fa-terminal fa-fw "></i>
                <?= Html::encode($this->title) ?>
            </h1>
        </div>
        <div class="col-xs-12 col-sm-5 col-md-4 col-lg-3"> <?php echo $this->render('_search'); ?> </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div>
                <?= LinkSorter::widget([
                    'sort' => $dataProvider->getSort(),
                    'linkOptions' => [
                        'class' => 'sort-item',
                    ],
                    'attributes' => [
                        'created_at',
                        'updated_at'
                    ]
                ]); ?>
                <div class="g-l-ico"><i class="fa fa-venus"></i>
                    <a href="<?=Url::current(['female' => true]);?>" class="label only-girl"><?=Yii::t('user','Just look ladies')?></a>
                </div>
                <div class="g-l-ico">
                    <a class="label only-girl all-coder" href="<?= Url::to(['/user/people/index']); ?>"><span>All</span>
                        <i class="fa fa-mail-reply-all"></i></a></div>
                <div class="totol-users"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12">
            <div class="well">
                <?php
                if (isset($params['province_id']) ||
                    isset($params['area_id']) ||
                    isset($params['occupation_id'])  ||
                    isset($params['direction_id']) ||
                    isset($params['current'])
                ) :
                    ?>
                    <div class="coder-filter">
                        <span class="glyphicon glyphicon-filter"></span><span>已选：</span>
                        <ul class="filter-list">
                            <?php if (isset($params['province_id'])) :
                                ?>
                                <li>
                                    <a href="<?=Url::current(['province_id' => null]);?>"><?=Html::encode($province->name);?><span class="f-close"></span></a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($params['area_id'])) :
                                ?>
                                <li>
                                    <a href="<?=Url::current(['area_id' => null]);?>"><?=Html::encode($area->name);?><span class="f-close"></span></a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($params['occupation_id'])) :
                                ?>
                                <li>
                                    <a href="<?=Url::current(['occupation_id' => null]);?>"><?=Html::encode($occupation->name);?><span class="f-close"></span></a>
                                </li>
                            <?php endif; ?>
                            <?php if (isset($params['direction_id'])) :
                                ?>
                                <li>
                                    <a href="<?=Url::current(['direction_id' => null]);?>"><?=Html::encode($direction->name);?><span class="f-close"></span></a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <?php
                endif;
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'itemView' => '_item',//子视图
                    'options' => ['class' => 'user-list'],
                    'itemOptions'=>['class'=>'media'],
                    //'layout' => "{sorter}\n{items}\n{pager}",
                    //'layout' => "{items}\n{pager}",
                    'viewParams' => [
                        'params' => $params,
                    ]
                ]);
                ?>
            </div>
        </article>
    </div>
<?php
echo ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_item',//子视图

    //'layout' => "{sorter}\n{items}\n{pager}",
    'layout' => "{items}\n{pager}",
//    'viewParams' => [
//        'params' => $params,
//    ]
]);
?>