<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\clients;

use Yii;
use xutl\authclient\OSChina as BaseOSChina;

/**
 * Class OSChina
 * @package yuncms\user\clients
 */
class OSChina extends BaseOSChina implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultName() {
        return Yii::t('user','OSChina');
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
        return isset($this->getUserAttributes()['username']) ? $this->getUserAttributes()['username'] : null;
    }
}