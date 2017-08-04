<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class TokenFixture extends ActiveFixture
{
    public $modelClass = 'yuncms\user\models\Token';

    public $depends = [
        'tests\_fixtures\UserFixture'
    ];
}
