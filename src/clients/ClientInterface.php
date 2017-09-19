<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\clients;

use yii\authclient\ClientInterface as BaseInterface;

/**
 * Enhances default yii client interface by adding methods that can be used to
 * get user's email and username.
 *
 */
interface ClientInterface extends BaseInterface
{

    /**
     * @return string|null User's email
     */
    public function getEmail();

    /**
     * @return string|null User's username
     */
    public function getUsername();
}
