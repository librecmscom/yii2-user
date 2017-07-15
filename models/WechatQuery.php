<?php

namespace yuncms\user\models;

use yii\authclient\ClientInterface;

/**
 * This is the ActiveQuery class for [[Wechat]].
 *
 * @see Wechat
 */
class WechatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Wechat[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Wechat|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * Finds an account by code.
     * @param string $code
     * @return WechatQuery
     */
    public function byCode($code)
    {
        return $this->andWhere(['code' => md5($code)]);
    }

    /**
     * Finds an account by client.
     * @param ClientInterface $client
     * @return WechatQuery
     */
    public function byClient(ClientInterface $client)
    {
        return $this->orWhere([
            'openid' => $client->getUserAttributes()['openid'],
            'unionid' => $client->getUserAttributes()['unionid']
        ]);
    }
}
