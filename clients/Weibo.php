<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use xutl\authClient\Weibo as BaseWeiBo;


class Weibo extends BaseWeiBo implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return ;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['username']) ? $this->getUserAttributes()['username'] : null;
    }
}