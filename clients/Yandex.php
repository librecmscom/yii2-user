<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use Yii;
use yii\authclient\clients\Yandex as BaseYandex;

/**
 * Class Yandex
 * @package yuncms\user
 */
class Yandex extends BaseYandex implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        $emails = isset($this->getUserAttributes()['emails']) ? $this->getUserAttributes()['emails'] : null;

        if ($emails !== null && isset($emails[0])) {
            return $emails[0];
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['login']) ? $this->getUserAttributes()['login'] : null;
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return Yii::t('user', 'Yandex');
    }
}
