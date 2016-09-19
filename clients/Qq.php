<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use xutl\authclient\Qq as BaseQq;

class Qq extends BaseQq implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['username']) ? $this->getUserAttributes()['username'] : null;
    }
}