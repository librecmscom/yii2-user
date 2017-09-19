<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use yii\db\ActiveQuery;
use yii\authclient\ClientInterface;

/**
 * @method Social|null one($db = null)
 * @method Social[] all($db = null)
 */
class SocialQuery extends ActiveQuery
{
    /**
     * Finds an account by code.
     * @param string $code
     * @return SocialQuery
     */
    public function byCode($code)
    {
        return $this->andWhere(['code' => md5($code)]);
    }

    /**
     * Finds an account by id.
     * @param integer $id
     * @return SocialQuery
     */
    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }

    /**
     * Finds an account by user_id.
     * @param integer $userId
     * @return SocialQuery
     */
    public function byUser($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * Finds an account by client.
     * @param ClientInterface $client
     * @return SocialQuery
     */
    public function byClient(ClientInterface $client)
    {
        return $this->andWhere(['provider' => $client->getId(),'client_id' => $client->getUserAttributes()['id']]);
    }
}