<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use yii\helpers\ArrayHelper;
use yii\authclient\clients\Twitter as BaseTwitter;

class Twitter extends BaseTwitter implements ClientInterface
{
    /**
     * @return string
     */
    public function getUsername()
    {
        return ArrayHelper::getValue($this->getUserAttributes(), 'screen_name');
    }

    /**
     * @return null Twitter does not provide user's email address
     */
    public function getEmail()
    {
        return null;
    }
}
