<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\authclient\ClientInterface as BaseClientInterface;
use yuncms\user\clients\ClientInterface;

/**
 * @property integer $id Id
 * @property integer $user_id User id, null if account is not bind to user
 * @property string $provider Name of service
 * @property string $client_id Account id
 * @property string $data Account properties returned by social network (json encoded)
 * @property string $decodedData Json-decoded properties
 * @property string $code
 * @property integer $created_at
 * @property string $email
 * @property string $username
 *
 * @property User $user User that this account is connected for.
 */
class Social extends ActiveRecord
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
        return '{{%user_social_account}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                ],
            ]
        ];
    }

    /**
     * 定义用户关系
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
     * Returns connect url.
     * @return string
     */
    public function getConnectUrl()
    {
        $code = Yii::$app->security->generateRandomString();
        $this->updateAttributes(['code' => md5($code)]);

        return Url::to(['/user/registration/connect', 'code' => $code]);
    }

    public function connect(User $user)
    {
        return $this->updateAttributes(['username' => null, 'email' => null, 'code' => null, 'user_id' => $user->id]);
    }

    public static function find()
    {
        return Yii::createObject(SocialQuery::className(), [get_called_class()]);
    }

    public static function create(BaseClientInterface $client)
    {
        /** @var Social $account */
        $account = Yii::createObject([
            'class' => static::className(),
            'provider' => $client->getId(),
            'client_id' => $client->getUserAttributes()['id'],
            'data' => json_encode($client->getUserAttributes())
        ]);

        if ($client instanceof ClientInterface) {
            $account->setAttributes(['username' => $client->getUsername(), 'email' => $client->getEmail()], false);
        }

        if (($user = static::fetchUser($account)) instanceof User) {
            $account->user_id = $user->id;
        }

        $account->save(false);

        return $account;
    }

    /**
     * Tries to find an account and then connect that account with current user.
     *
     * @param BaseClientInterface $client
     */
    public static function connectWithUser(BaseClientInterface $client)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Something went wrong'));
            return;
        }

        $account = static::fetchAccount($client);

        if ($account->user === null) {
            $account->link('user', Yii::$app->user->identity);
            Yii::$app->session->setFlash('success', Yii::t('user', 'Your account has been connected'));
        } else {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'This account has already been connected to another user'));
        }
    }

    /**
     * Tries to find account, otherwise creates new account.
     *
     * @param BaseClientInterface $client
     *
     * @return Social
     * @throws \yii\base\InvalidConfigException
     */
    protected static function fetchAccount(BaseClientInterface $client)
    {
        $account = Social::find()->byClient($client)->one();
        if (null === $account) {
            $account = Yii::createObject(['class' => static::className(), 'provider' => $client->getId(), 'client_id' => $client->getUserAttributes()['id'], 'data' => json_encode($client->getUserAttributes())]);
            $account->save(false);
        }

        return $account;
    }

    /**
     * Tries to find user or create a new one.
     *
     * @param Social $account
     *
     * @return User|boolean False when can't create user.
     */
    protected static function fetchUser(Social $account)
    {
        $user = User::findByEmail($account->email);

        if (null !== $user) {
            return $user;
        }

        /** @var \yuncms\user\models\User $user */
        $user = Yii::createObject([
            'class' => User::className(),
            'scenario' => 'connect',
            'username' => $account->username,
            'email' => $account->email
        ]);

        if (!$user->validate(['email'])) {
            $account->email = null;
        }

        if (!$user->validate(['name'])) {
            $account->username = null;
        }

        return $user->create() ? $user : false;
    }
}
