<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use Yii;
use xutl\authclient\Weibo as BaseWeiBo;

/**
 * Class Weibo
 * @package yuncms\user\clients
 */
class Weibo extends BaseWeiBo implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('user', 'Weibo');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['name']) ? $this->getUserAttributes()['name'] : null;
    }
}