<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use Yii;
use xutl\authclient\Douban as BaseDouBan;

class Douban extends BaseDouBan implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return Yii::t('user','Douban');
    }
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return null;
    }
}