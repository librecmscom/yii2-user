<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use Yii;
use yii\authclient\clients\Google as BaseGoogle;

/**
 * Class Google
 * @package yuncms\user
 */
class Google extends BaseGoogle implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('user', 'Google');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['emails'][0]['value']) ? $this->getUserAttributes()['emails'][0]['value'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return null;
    }
}
