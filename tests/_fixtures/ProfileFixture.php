<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class ProfileFixture extends ActiveFixture
{
    public $modelClass = 'yuncms\user\models\Profile';

    public $depends = [
        'tests\_fixtures\UserFixture'
    ];
}
