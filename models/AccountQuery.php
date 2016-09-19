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
 * @method Account|null one($db = null)
 * @method Account[] all($db = null)
 */
class AccountQuery extends ActiveQuery
{
    /**
     * Finds an account by code.
     * @param string $code
     * @return AccountQuery
     */
    public function byCode($code)
    {
        return $this->andWhere(['code' => md5($code)]);
    }

    /**
     * Finds an account by id.
     * @param integer $id
     * @return AccountQuery
     */
    public function byId($id)
    {
        return $this->andWhere(['id' => $id]);
    }

    /**
     * Finds an account by user_id.
     * @param integer $userId
     * @return AccountQuery
     */
    public function byUser($userId)
    {
        return $this->andWhere(['user_id' => $userId]);
    }

    /**
     * Finds an account by client.
     * @param ClientInterface $client
     * @return AccountQuery
     */
    public function byClient(ClientInterface $client)
    {
        return $this->andWhere(['provider' => $client->getId(),'client_id' => $client->getUserAttributes()['id']]);
    }
}