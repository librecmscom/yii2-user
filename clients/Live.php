<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\clients;

use yii\authclient\clients\Live as BaseLive;

class Live extends BaseLive implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['account']) ? $this->getUserAttributes()['account'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }
}