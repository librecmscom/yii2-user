<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\clients;

use Yii;
use yii\authclient\clients\LinkedIn as BaseLinkedIn;

/*
 * @author Sam Mousa <sam@mousa.nl>
 */
class LinkedIn extends BaseLinkedIn implements ClientInterface
{
    /**
     * @inheritdoc
     */
    protected function defaultTitle() {
        return Yii::t('user','LinkedIn');
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email-address']) ? $this->getUserAttributes()['email-address'] : null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['first-name']) ? $this->getUserAttributes()['first-name'] : null;
    }
}
