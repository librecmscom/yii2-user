<?php

namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yii\authclient\ClientInterface;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%user_wechat}}".
 *
 * @property int $id 主键
 * @property int $user_id 已经绑定的用户ID
 * @property string $openid openid
 * @property string $unionid 联合ID
 * @property string $access_token 访问令牌
 * @property int $expires_in 过期时间
 * @property string $refresh_token 刷新令牌
 * @property string $scope Scope
 * @property string $nickname 昵称
 * @property int $sex 性别
 * @property string $language 语言
 * @property string $country 国家
 * @property string $city 城市
 * @property string $province 省份
 * @property string $headimgurl 头像Url
 * @property string $data 原始数据
 * @property string $code 功能字段
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 *
 * @property User $user
 */
class Wechat extends ActiveRecord
{
    /**
     * @var
     */
    private $_data;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_wechat}}';
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
    public function rules()
    {
        return [
            [['user_id', 'expires_in', 'sex'], 'integer'],
            [['openid'], 'required'],
            [['data'], 'string'],
            [['openid', 'unionid'], 'string', 'max' => 64],
            [['access_token', 'refresh_token', 'headimgurl'], 'string', 'max' => 255],
            [['scope', 'city', 'province'], 'string', 'max' => 50],
            [['nickname'], 'string', 'max' => 100],
            [['language'], 'string', 'max' => 10],
            [['country'], 'string', 'max' => 3],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'Id'),
            'user_id' => Yii::t('user', 'User Id'),
            'openid' => Yii::t('user', 'Open Id'),
            'unionid' => Yii::t('user', 'Union Id'),
            'access_token' => Yii::t('user', 'Access Token'),
            'expires_in' => Yii::t('user', 'Expires In'),
            'refresh_token' => Yii::t('user', 'Refresh Token'),
            'scope' => Yii::t('user', 'Scope'),
            'nickname' => Yii::t('user', 'Nickname'),
            'sex' => Yii::t('user', 'Sex'),
            'language' => Yii::t('user', 'Language'),
            'country' => Yii::t('user', 'Country'),
            'city' => Yii::t('user', 'City'),
            'province' => Yii::t('user', 'Province'),
            'headimgurl' => Yii::t('user', 'Head Url'),
            'data' => Yii::t('user', 'Data'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return boolean Whether this social account is connected to user.
     */
    public function getIsConnected()
    {
        return $this->user_id != null;
    }

    /**
     * @return mixed Json decoded properties.
     */
    public function getDecodedData()
    {
        if ($this->_data == null) {
            $this->_data = Json::decode($this->data);
        }
        return $this->_data;
    }

    /**
     * @inheritdoc
     * @return WechatQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WechatQuery(get_called_class());
    }

    /**
     * Returns connect url.
     * @return string
     */
    public function getConnectUrl()
    {
        $code = Yii::$app->security->generateRandomString();
        $this->updateAttributes(['code' => md5($code)]);
        return Url::to(['/user/security/connect', 'code' => $code]);
    }

    /**
     * @param ClientInterface $client
     * @return Social|Wechat
     */
    public static function create(ClientInterface $client)
    {
        /** @var array $accountTokenParams */
        $accountTokenParams = $client->getAccessToken()->getParams();
        $userAttributes = $client->getUserAttributes();
        /** @var Social $account */
        $account = Yii::createObject([
            'class' => static::className(),
            'openid' => $userAttributes['openid'],
            'unionid' => isset($userAttributes['unionid']) ? $userAttributes['unionid'] : Null,
            'access_token' => $accountTokenParams['access_token'],
            'expires_in' => $accountTokenParams['expires_in'] - 1500 + time(),
            'refresh_token' => $accountTokenParams['refresh_token'],
            'scope' => $accountTokenParams['scope'],
            'nickname' => $userAttributes['nickname'],
            'sex' => $userAttributes['sex'],
            'language' => $userAttributes['language'],
            'city' => $userAttributes['city'],
            'province' => $userAttributes['province'],
            'country' => $userAttributes['country'],
            'headimgurl' => $userAttributes['headimgurl'],
            'data' => Json::encode($userAttributes)
        ]);
        $account->save(false);
        return $account;
    }

    public function connect(User $user)
    {
        return $this->updateAttributes(['code' => null, 'user_id' => $user->id]);
    }
}
