<?php

namespace yuncms\user\frontend\widgets;

use yii\base\Widget;
use yuncms\user\models\User;

/**
 * Class Popular
 * @package yuncms\user\frontend\widgets
 */
class Popular extends Widget
{
    public $limit = 10;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $models = User::find()->with('profile')
            ->limit($this->limit)
            ->all();

        return $this->render('popular', [
            'models' => $models,
        ]);
    }
}
