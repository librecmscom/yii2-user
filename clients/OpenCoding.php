<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\clients;

use yii\authclient\OAuth2;

class OpenCoding extends OAuth2 implements ClientInterface
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://www.opencoding.com/user/oauth2/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://www.opencoding.com/user/oauth2/access_token';

    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://www.opencoding.com/user/api';

    public $attributeNames = [
        'name',
        'email',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = 'user';
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->api('me', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'opencoding';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'opencoding';
    }

    /** @inheritdoc */
    public function getEmail()
    {
        return isset($this->getUserAttributes()['email']) ? $this->getUserAttributes()['email'] : null;
    }

    /** @inheritdoc */
    public function getUsername()
    {
        return isset($this->getUserAttributes()['username']) ? $this->getUserAttributes()['username'] : null;
    }
}