<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\filters\RateLimitInterface;
use yii\behaviors\TimestampBehavior;

/**
 * Rest Model
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $auth_key
 * @property string $token
 * @property int $rate_limit
 * @property int $rate_period
 * @property int $created_at
 * @property int $updated_at
 *
 * @package yuncms\user
 */
class Rest extends ActiveRecord implements IdentityInterface, RateLimitInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_rest}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [TimestampBehavior::className()];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * 获取auth_key
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        if (strpos($token, ',') != false) {
            list($id, $token) = explode(',', $token, 2);
            return static::findOne(['id' => $id, 'token' => $token]);
        } else {
            return static::findOne(['token' => $token]);
        }
    }

    /**
     * 返回允许的请求的最大数目及时间，例如，[100, 600] 表示在600秒内最多100次的API调用。
     * @param \yii\web\Request $request
     * @param $action
     * @return array
     */
    public function getRateLimit($request, $action)
    {
        return [$this->rate_limit, $this->rate_period];
    }

    /**
     * 返回剩余的允许的请求和相应的UNIX时间戳数 当最后一次速率限制检查时。
     * @param \yii\web\Request $request
     * @param $action
     * @return array
     */
    public function loadAllowance($request, $action)
    {
        return [
            Yii::$app->cache->get($this->getId() . '_allowance'),
            Yii::$app->cache->get($this->getId() . '_allowance_updated_at')
        ];
    }

    /**
     * 保存允许剩余的请求数和当前的UNIX时间戳。
     * @param \yii\web\Request $request
     * @param $action
     * @param $allowance
     * @param $timestamp
     */
    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        Yii::$app->cache->set($this->getId() . '_allowance', $allowance);
        Yii::$app->cache->set($this->getId() . '_allowance_updated_at', $timestamp);
    }

    /** @inheritdoc */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', Yii::$app->security->generateRandomString());
        }
        return parent::beforeSave($insert);
    }
}