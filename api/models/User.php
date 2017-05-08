<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\api\models;

use Yii;
use yii\web\Link;
use yii\web\Linkable;
use yii\helpers\Url;

/**
 * Class User
 * @package yuncms\user\api\models
 */
class User extends \yuncms\user\models\User implements Linkable
{
    /**
     * @return array
     */
    public function fields()
    {
        return [
            //'user_id' => 'id',
            'username',
            'email'
        ];
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['/user/user/view', 'id' => $this->id], true),
        ];
    }
}