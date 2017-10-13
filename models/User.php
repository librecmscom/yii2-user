<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\db\ActiveQuery;
use yii\helpers\Inflector;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\web\Application as WebApplication;
use yuncms\oauth2\OAuth2IdentityInterface;
use yuncms\user\models\Settings;
use yuncms\user\Module;
use yuncms\user\UserTrait;
use yuncms\user\frontend\assets\UserAsset;
use yuncms\tag\models\Tag;
use yuncms\user\helpers\Password;

/**
 * User ActiveRecord model.
 *
 * @property bool $isBlocked 是否已经锁定
 * @property bool $isMobileConfirmed 是否已经手机激活
 * @property bool $isEmailConfirmed 是否已经邮箱激活
 * @property bool $isAvatar 是否有头像
 *
 * Database fields:
 * @property integer $id ID 唯一
 * @property string $username 用户标识唯一
 * @property string $email 邮箱唯一
 * @property string $mobile 用户手机唯一
 * @property string $nickname 用户用户昵称不唯一
 * @property string $password 密码
 * @property string $unconfirmed_email 未验证激活的邮箱
 * @property string $unconfirmed_mobile 未验证激活的手机
 * @property string $password_hash 密码哈希
 * @property string $auth_key 认证码
 * @property bool $avatar 是否有头像
 * @property integer $registration_ip 注册IP
 * @property integer $email_confirmed_at 邮件激活时间
 * @property integer $mobile_confirmed_at 手机激活时间
 * @property integer $blocked_at 账户封杀时间
 * @property integer $created_at 账户创建时间
 * @property integer $updated_at 账户更新时间
 * @property integer $flags 标记
 * @property integer $ver 版本
 *
 * Defined relations:
 * @property Social[] $accounts 社交账号
 * @property Profile $profile 个人资料
 * @property Extend $extend 延伸资料
 *
 * Dependencies:
 * @property-read Module $module
 *
 */
class User extends ActiveRecord implements IdentityInterface, OAuth2IdentityInterface
{
    use UserTrait;

    const BEFORE_CREATE = 'beforeCreate';
    const AFTER_CREATE = 'afterCreate';
    const BEFORE_REGISTER = 'beforeRegister';
    const AFTER_REGISTER = 'afterRegister';

    // following constants are used on secured email changing process
    const OLD_EMAIL_CONFIRMED = 0b1;
    const NEW_EMAIL_CONFIRMED = 0b10;

    // following constants are used on secured mobile changing process
    const OLD_MOBILE_CONFIRMED = 0b11;
    const NEW_MOBILE_CONFIRMED = 0b100;

    const AVATAR_BIG = 'big';
    const AVATAR_MIDDLE = 'middle';
    const AVATAR_SMALL = 'small';

    /**
     * @var string Plain password. Used for model validation.
     */
    public $password;

    /**
     * @var Profile|null
     */
    private $_profile;

    /** @var  Extend|null */
    private $_extend;

    /**
     * @var string Default username regexp
     */
    public static $usernameRegexp = '/^[-a-zA-Z0-9_]+$/u';

    /**
     * @var string Default nickname regexp
     */
    public static $nicknameRegexp = '/^[-a-zA-Z0-9_\x{4e00}-\x{9fa5}\.@]+$/u';

    public static $mobileRegexp = '/^13[\d]{9}$|^15[\d]{9}$|^17[\d]{9}$|^18[\d]{9}$/';

    /**
     * @return string
     * @deprecated
     */
    public function getSlug(){
        return $this->username;
    }

    /**
     * @param $slug
     * @deprecated
     */
    public function setSlug($slug){
        $this->username = $slug;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior'
            ],
            'taggable' => [
                'class' => 'yuncms\tag\behaviors\TagBehavior',
                'tagValuesAsArray' => true,
                'tagRelation' => 'tags',
                'tagValueAttribute' => 'id',
                'tagFrequencyAttribute' => 'frequency',
            ],
        ];
    }

    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nickname' => Yii::t('user', 'Nickname'),
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'mobile' => Yii::t('user', 'Mobile'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
            'unconfirmed_email' => Yii::t('user', 'New email'),
            'password' => Yii::t('user', 'Password'),
            'created_at' => Yii::t('user', 'Registration time'),
            'confirmed_at' => Yii::t('user', 'Confirmation time'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        return ArrayHelper::merge($scenarios, [
            'register' => ['username', 'email', 'password'],
            'connect' => ['username', 'email'],
            'create' => ['username', 'email', 'password'],
            'update' => ['username', 'email', 'password'],
            'settings' => ['username', 'email', 'password'],
            'import' => ['username', 'email', 'password'],
            'mobile_register' => ['mobile', 'username', 'password'],
            'wechat_connect' => ['username', 'email', 'password'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username rules
            'usernameMatch' => ['username', 'match', 'pattern' => static::$usernameRegexp],
            'usernameLength' => ['username', 'string', 'min' => 3, 'max' => 50],
            'usernameUnique' => ['username', 'unique', 'message' => Yii::t('user', 'This username has already been taken')],
            'usernameTrim' => ['username', 'trim'],

            // nickname rules
            'nicknameRequired' => ['nickname', 'required', 'on' => ['register', 'create', 'connect', 'update', 'mobile_register', 'wechat_register']],
            'nicknameMatch' => ['nickname', 'match', 'pattern' => static::$nicknameRegexp],
            'nicknameLength' => ['nickname', 'string', 'min' => 3, 'max' => 255],
            'nicknameUnique' => ['nickname', 'unique', 'message' => Yii::t('user', 'This nickname has already been taken')],
            'nicknameTrim' => ['nickname', 'trim'],

            // email rules
            'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'emailPattern' => ['email', 'email', 'checkDNS' => true],
            'emailLength' => ['email', 'string', 'max' => 255],
            'emailUnique' => ['email', 'unique', 'message' => Yii::t('user', 'This email address has already been taken')],
            'emailTrim' => ['email', 'trim'],
            'emailDefault' => ['email', 'default', 'value' => null, 'on' => ['mobile_register']],

            //mobile rules
            'mobileRequired' => ['mobile', 'required', 'on' => ['mobile_register']],
            'mobilePattern' => ['mobile', 'match', 'pattern' => static::$mobileRegexp],
            'mobileLength' => ['mobile', 'string', 'max' => 11],
            'mobileUnique' => ['mobile', 'unique', 'message' => Yii::t('user', 'This mobile address has already been taken')],
            'mobileDefault' => ['email', 'default', 'value' => null, 'on' => ['register', 'create']],

            // password rules
            'passwordRequired' => ['password', 'required', 'on' => ['register', 'mobile_register']],
            'passwordLength' => ['password', 'string', 'min' => 6, 'on' => ['register', 'create']],

            'tags' => ['tagValues', 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * 获取auth_key
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * 验证密码
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * 验证AuthKey
     * @param string $authKey
     * @return boolean
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 创建 "记住我" 身份验证Key
     * @return void
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * 返回用户邮箱是否已经激活
     * @return boolean Whether the user is confirmed or not.
     */
    public function getIsEmailConfirmed()
    {
        return $this->email_confirmed_at != null;
    }

    /**
     * 返回用户手机是否已经激活
     * @return boolean Whether the user is confirmed or not.
     */
    public function getIsMobileConfirmed()
    {
        return $this->mobile_confirmed_at != null;
    }

    /**
     * 返回用户是否已经锁定
     * @return boolean Whether the user is blocked or not.
     */
    public function getIsBlocked()
    {
        return $this->blocked_at != null;
    }

    /**
     * 返回用户是否有头像
     * @return boolean Whether the user is blocked or not.
     */
    public function getIsAvatar()
    {
        return $this->avatar != 0;
    }

    /**
     * 定义用户详细资料关系
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * 设置用户资料
     * @param Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->_profile = $profile;
    }

    /**
     * 定义延伸资料关系
     * @return ActiveQuery
     */
    public function getExtend()
    {
        return $this->hasOne(Extend::className(), ['user_id' => 'id']);
    }

    /**
     * 设置用户延伸资料
     * @param Extend $extend
     */
    public function setExtend($extend)
    {
        $this->_extend = $extend;
    }

    /**
     * 定义微信端关系
     * @return \yii\db\ActiveQuery
     */
    public function getWechat()
    {
        return $this->hasOne(Wechat::className(), ['user_id' => 'id']);
    }

    /**
     * 定义用户Tag关系
     * @return \yii\db\ActiveQuery
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%user_tag}}', ['user_id' => 'id']);
    }

    /**
     * 定义用户教育经历关系
     * @return \yii\db\ActiveQuery
     */
    public function getEducations()
    {
        return $this->hasMany(Education::className(), ['user_id' => 'id']);
    }

    /**
     * 定义工作经历关系
     * @return \yii\db\ActiveQuery
     */
    public function getCareers()
    {
        return $this->hasMany(Career::className(), ['user_id' => 'id']);
    }

    /**
     * 定义登录历史关系
     * @return \yii\db\ActiveQuery
     */
    public function getLoginHistories()
    {
        return $this->hasMany(LoginHistory::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我的访客
     * 一对多关系
     */
    public function getVisits()
    {
        return $this->hasMany(Visit::className(), ['source_id' => 'id']);
    }

    /**
     * 返回所有已经连接的社交媒体账户
     * @return Social[] Connected accounts ($provider => $account)
     */
    public function getAccounts()
    {
        $connected = [];
        /** @var Social[] $accounts */
        $accounts = $this->hasMany(Social::className(), ['user_id' => 'id'])->all();
        /**
         * @var Social $account
         */
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /**
     * 获取我的收藏
     * 一对多关系
     */
    public function getCollections()
    {
        return $this->hasMany(\yuncms\collection\models\Collection::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我的关注一对多关系
     */
    public function getAttentions()
    {
        return $this->hasMany(\yuncms\attention\models\Attention::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我关注
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFollowers()
    {
        return $this->hasMany(\yuncms\attention\models\Follow::className(), ['user_id' => 'id'])->andOnCondition(['model' => get_class($this)]);
    }

    /**
     * 用户我的粉丝
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFans()
    {
        return $this->hasMany(\yuncms\attention\models\Attention::className(), ['model_id' => 'id'])->andOnCondition(['model' => get_class($this)]);
    }


    /**
     * 设置最后登录时间
     * @return void
     */
    public function resetLoginData()
    {
        $this->extend->updateAttributes(['login_at' => time()]);
        $this->extend->updateAttributes(['login_ip' => Yii::$app->request->userIP]);
        $this->extend->updateCounters(['login_num' => 1]);
    }

    /**
     * 重置密码
     *
     * @param string $password
     *
     * @return boolean
     */
    public function resetPassword($password)
    {
        return (bool)$this->updateAttributes(['password_hash' => Password::hash($password)]);
    }

    /**
     * 锁定用户
     * @return boolean
     */
    public function block()
    {
        return (bool)$this->updateAttributes(['blocked_at' => time(), 'auth_key' => Yii::$app->security->generateRandomString()]);
    }

    /**
     * 解除用户锁定
     * @return boolean
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
    }

    /**
     * 设置Email已经验证
     * @return bool
     */
    public function setEmailConfirm()
    {
        return (bool)$this->updateAttributes(['email_confirmed_at' => time()]);
    }

    /**
     * 设置手机号已经验证
     * @return bool
     */
    public function setMobileConfirm()
    {
        return (bool)$this->updateAttributes(['mobile_confirmed_at' => time()]);
    }

    /**
     * 是否已经收藏过Source和ID
     * @param string $model
     * @param int $modelId
     * @return bool
     */
    public function isCollected($model, $modelId)
    {
        return $this->getCollections()->andWhere(['model' => $model, 'model_id' => $modelId])->exists();
    }

    /**
     * 是否已关注指定的Source和ID
     * @param string $model
     * @param int $modelId
     * @return mixed
     */
    public function isFollowed($model, $modelId)
    {
        return $this->getAttentions()->andWhere(['model' => $model, 'model_id' => $modelId])->exists();
    }

    /**
     * 获取头像Url
     * @param string $size
     * @return string
     */
    public function getAvatar($size = self::AVATAR_MIDDLE)
    {
        $size = in_array($size, ['big', 'middle', 'small']) ? $size : 'big';
        if ($this->getIsAvatar()) {
            $avatarFileName = "_avatar_$size.jpg";
            return $this->getAvatarUrl($this->id) . $avatarFileName;
        } else {
            switch ($size) {
                case 'big':
                    $avatarUrl = '/img/no_avatar_big.gif';
                    break;
                case 'middle':
                    $avatarUrl = '/img/no_avatar_middle.gif';
                    break;
                case 'small':
                    $avatarUrl = '/img/no_avatar_small.gif';
                    break;
                default:
                    $avatarUrl = '/img/no_avatar_big.gif';
            }
            if (Yii::getAlias('@webroot', false)) {
                $baseUrl = UserAsset::register(Yii::$app->view)->baseUrl;
                return Url::to($baseUrl . $avatarUrl, true);
            } else {
                return '';
            }
        }
    }

    /**
     * 获取用户已经激活的钱包
     * @return null|ActiveQuery
     */
    public function getWallets()
    {
        if (Yii::$app->hasModule('wallet')) {
            return $this->hasMany(\yuncms\wallet\models\Wallet::className(), ['user_id' => 'id']);
        }
        return null;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return Yii::$app->getModule('user');
    }

    /**
     * 创建新用户帐户。 如果用户不提供密码，则会生成密码。
     *
     * @return boolean
     */
    public function create()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->email_confirmed_at = time();
        $this->password = $this->password == null ? Password::generate(8) : $this->password;
        $this->username = $this->username == null ? Inflector::slug($this->nickname, '-') : $this->username;
        $this->trigger(self::BEFORE_CREATE);
        if (!$this->save()) {
            return false;
        }
        $this->module->sendMessage($this->email, Yii::t('user', 'Welcome to {0}', Yii::$app->name), 'welcome', ['user' => $this, 'token' => null, 'module' => $this->module, 'showPassword' => true]);
        $this->trigger(self::AFTER_CREATE);
        return true;
    }

    /**
     * 此方法用于注册新用户帐户。 如果Module :: enableConfirmation设置为true，则此方法
     * 将生成新的确认令牌，并使用邮件发送给用户。
     *
     * @return boolean
     */
    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }
        $this->email_confirmed_at = $this->getSetting('enableConfirmation') ? null : time();
        $this->password = $this->getSetting('enableGeneratingPassword') ? Password::generate(8) : $this->password;
        $this->username = $this->username == null ? Inflector::slug($this->nickname, '-') : $this->username;

        $this->trigger(self::BEFORE_REGISTER);
        if (!$this->save()) {
            return false;
        }
        if ($this->getSetting('enableConfirmation') && !empty($this->email)) {
            /** @var Token $token */
            $token = new Token(['type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
            $this->module->sendMessage($this->email, Yii::t('user', 'Welcome to {0}', Yii::$app->name), 'welcome', ['user' => $this, 'token' => isset($token) ? $token : null, 'module' => $this->module, 'showPassword' => false]);
        } else {
            Yii::$app->user->login($this, $this->getSetting('rememberFor'));
        }

        $this->trigger(self::AFTER_REGISTER);
        return true;
    }

    /**
     * 电子邮件激活
     *
     * @param string $code Confirmation code.
     *
     * @return boolean
     */
    public function attemptConfirmation($code)
    {
        $token = Token::findOne(['user_id' => $this->id, 'code' => $code, 'type' => Token::TYPE_CONFIRMATION]);
        if ($token instanceof Token && !$token->isExpired) {
            $token->delete();
            if (($success = $this->setEmailConfirm())) {
                Yii::$app->user->login($this, $this->getSetting('rememberFor'));
                $message = Yii::t('user', 'Thank you, registration is now complete.');
            } else {
                $message = Yii::t('user', 'Something went wrong and your account has not been confirmed.');
            }
        } else {
            $success = false;
            $message = Yii::t('user', 'The confirmation link is invalid or expired. Please try requesting a new one.');
        }
        Yii::$app->session->setFlash($success ? 'success' : 'danger', $message);
        return $success;
    }

    /**
     * 该方法将更新用户的电子邮件，如果`unconfirmed_email`字段为空将返回false,如果该邮件已经有人使用了将返回false; 否则返回true
     *
     * @param string $code
     *
     * @return boolean
     * @throws \Exception
     */
    public function attemptEmailChange($code)
    {
        /** @var Token $token */
        $token = Token::find()->where(['user_id' => $this->id, 'code' => $code])->andWhere(['in', 'type', [Token::TYPE_CONFIRM_NEW_EMAIL, Token::TYPE_CONFIRM_OLD_EMAIL]])->one();
        if (empty($this->unconfirmed_email) || $token === null || $token->isExpired) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Your confirmation token is invalid or expired'));
        } else {
            $token->delete();
            if (empty($this->unconfirmed_email)) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred processing your request'));
            } elseif (static::find()->where(['email' => $this->unconfirmed_email])->exists() == false) {
                if (Yii::$app->settings->get('emailChangeStrategy', 'user') == Settings::STRATEGY_SECURE) {
                    switch ($token->type) {
                        case Token::TYPE_CONFIRM_NEW_EMAIL:
                            $this->flags |= self::NEW_EMAIL_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user', 'Awesome, almost there. Now you need to click the confirmation link sent to your old email address'));
                            break;
                        case Token::TYPE_CONFIRM_OLD_EMAIL:
                            $this->flags |= self::OLD_EMAIL_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user', 'Awesome, almost there. Now you need to click the confirmation link sent to your new email address'));
                            break;
                    }
                }
                if (Yii::$app->settings->get('emailChangeStrategy', 'user') == Settings::STRATEGY_DEFAULT || ($this->flags & self::NEW_EMAIL_CONFIRMED && $this->flags & self::OLD_EMAIL_CONFIRMED)) {
                    $this->email = $this->unconfirmed_email;
                    $this->unconfirmed_email = null;
                    Yii::$app->session->setFlash('success', Yii::t('user', 'Your email address has been changed'));
                }
                $this->save(false);
            }
        }
    }

    /**
     * 该方法将更新用户的手机，如果`unconfirmed_mobile`字段为空将返回false,如果该手机已经有人使用了将返回false; 否则返回true
     *
     * @param string $code
     *
     * @return boolean
     * @throws \Exception
     */
    public function attemptMobileChange($code)
    {
        /** @var Token $token */
        $token = Token::find()->where([
            'user_id' => $this->id,
            'code' => $code
        ])->andWhere(['in', 'type', [Token::TYPE_CONFIRM_NEW_MOBILE, Token::TYPE_CONFIRM_OLD_MOBILE]])->one();
        if (empty($this->unconfirmed_mobile) || $token === null || $token->isExpired) {
            Yii::$app->session->setFlash('danger', Yii::t('user', 'Your confirmation token is invalid or expired'));
        } else {
            $token->delete();
            if (empty($this->unconfirmed_mobile)) {
                Yii::$app->session->setFlash('danger', Yii::t('user', 'An error occurred processing your request'));
            } elseif (static::find()->where(['mobile' => $this->unconfirmed_mobile])->exists() == false) {
                if (Yii::$app->settings->get('mobileChangeStrategy', 'user') == Settings::STRATEGY_SECURE) {
                    switch ($token->type) {
                        case Token::TYPE_CONFIRM_NEW_MOBILE:
                            $this->flags |= self::NEW_MOBILE_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user', 'Awesome, almost there. Now you need to input the confirmation code sent to your old mobile'));
                            break;
                        case Token::TYPE_CONFIRM_OLD_MOBILE:
                            $this->flags |= self::OLD_MOBILE_CONFIRMED;
                            Yii::$app->session->setFlash('success', Yii::t('user', 'Awesome, almost there. Now you need to input the confirmation code sent to your new mobile'));
                            break;
                    }
                }
                if (Yii::$app->settings->get('mobileChangeStrategy', 'user') == Settings::STRATEGY_DEFAULT || ($this->flags & self::NEW_MOBILE_CONFIRMED && $this->flags & self::OLD_MOBILE_CONFIRMED)) {
                    $this->mobile = $this->unconfirmed_mobile;
                    $this->unconfirmed_mobile = null;
                    Yii::$app->session->setFlash('success', Yii::t('user', 'Your mobile address has been changed'));
                }
                $this->save(false);
            }
        }
    }

    /**
     * 使用email地址生成一个新的用户昵称
     */
    public function generateUsername()
    {
        // try to use username part of email
        $this->username = explode('@', $this->email)[0];
        if ($this->validate(['username'])) {
            return $this->username;
        }

        // generate name like "user1", "user2", etc...
        while (!$this->validate(['username'])) {
            $row = (new Query())->from('{{%user}}')->select('MAX(id) as id')->one();
            $this->username = 'user' . ++$row['id'];
        }
        return $this->username;
    }

    /**
     * 保存前执行
     * @param bool $insert 是否是插入操作
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->generateAuthKey();
            if (Yii::$app instanceof WebApplication) {
                $this->registration_ip = Yii::$app->request->getUserIP();
            }
        }
        if (!empty($this->password)) {
            $this->password_hash = Password::hash($this->password);
        }
        return parent::beforeSave($insert);
    }

    /**
     * 保存后执行
     * @param bool $insert 是否是插入操作
     * @param array $changedAttributes 更改的属性
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            if ($this->_profile == null) {
                $this->_profile = new Profile();
            }
            $this->_profile->link('user', $this);

            if ($this->_extend == null) {
                $this->_extend = new Extend();
            }
            $this->_extend->link('user', $this);
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /**
     * 通过登陆邮箱或手机号获取用户
     * @param string $emailOrMobile
     * @return User|null
     */
    public static function findByEmailOrMobile($emailOrMobile)
    {
        if (filter_var($emailOrMobile, FILTER_VALIDATE_EMAIL)) {
            return static::findByEmail($emailOrMobile);
        } else if (preg_match(self::$mobileRegexp, $emailOrMobile)) {
            return static::findByMobile($emailOrMobile);
        }
        return null;
    }

    /**
     * 通过邮箱获取用户
     * @param string $email 邮箱
     * @return null|static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * 通过手机号获取用户
     * @param string $mobile
     * @return static
     */
    public static function findByMobile($mobile)
    {
        return static::findOne(['mobile' => $mobile]);
    }

    /**
     * 通过用户名获取用户
     * @param string $username 用户标识
     * @return null|static
     */
    public static function findModelByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }
}
