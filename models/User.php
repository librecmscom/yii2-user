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
use yuncms\user\Module;
use yuncms\user\ModuleTrait;
use yuncms\user\frontend\assets\UserAsset;
use yuncms\tag\models\Tag;
use yuncms\user\helpers\Password;

/**
 * User ActiveRecord model.
 *
 * @property bool $isBlocked 是否已经锁定
 * @property bool $isConfirmed 是否已经邮箱激活
 * @property bool $isAvatar 是否有头像
 *
 * Database fields:
 * @property integer $id ID 唯一
 * @property string $username 用户名唯一
 * @property string $email 邮箱唯一
 * @property string $mobile 用户手机唯一
 * @property string $nickname 昵称不唯一
 * @property string $password 密码
 * @property string $unconfirmed_email 未确认的邮箱
 * @property string $unconfirmed_mobile 未确认的手机
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
 * @property Data $userData 用户数据
 * @property Authentication $authentication 实名认证数据
 *
 * Dependencies:
 * @property-read Module $module
 *
 */
class User extends ActiveRecord implements IdentityInterface, OAuth2IdentityInterface
{
    use ModuleTrait;

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

    /**
     * @var Data|null
     */
    private $_userData;

    /**
     * @var string Default slug regexp
     */
    public static $slugRegexp = '/^[-a-zA-Z0-9_]+$/u';

    /**
     * @var string Default username regexp
     */
    public static $nameRegexp = '/^[-a-zA-Z0-9_\x{4e00}-\x{9fa5}\.@]+$/u';

    public static $mobileRegexp = '/^13[\d]{9}$|^15[\d]{9}$|^17[\d]{9}$|^18[\d]{9}$/';

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
            'name' => Yii::t('user', 'Name'),
            'email' => Yii::t('user', 'Email'),
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
            'register' => ['name', 'email', 'password'],
            'connect' => ['name', 'email'],
            'create' => ['name', 'email', 'password'],
            'update' => ['name', 'email', 'password'],
            'settings' => ['name', 'email', 'password'],
            'import' => ['name', 'email', 'password'],
            'mobile_register' => ['mobile', 'password'],
            'wechat_connect' => ['name', 'email', 'password'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // slug rules
            'slugMatch' => ['slug', 'match', 'pattern' => static::$slugRegexp],
            'slugLength' => ['slug', 'string', 'min' => 3, 'max' => 50],
            'slugUnique' => ['slug', 'unique', 'message' => Yii::t('user', 'This slug has already been taken')],
            'slugTrim' => ['slug', 'trim'],

            // name rules
            'nameRequired' => ['name', 'required', 'on' => ['register', 'create', 'connect', 'update', 'wechat_register']],
            'nameMatch' => ['name', 'match', 'pattern' => static::$nameRegexp],
            'nameLength' => ['name', 'string', 'min' => 3, 'max' => 255],
            'nameUnique' => ['name', 'unique', 'message' => Yii::t('user', 'This username has already been taken')],
            'nameTrim' => ['name', 'trim'],

            // email rules
            'emailRequired' => ['email', 'required', 'on' => ['register', 'connect', 'create', 'update']],
            'emailPattern' => ['email', 'email', 'checkDNS' => true],
            'emailLength' => ['email', 'string', 'max' => 255],
            'emailUnique' => ['email', 'unique', 'message' => Yii::t('user', 'This email address has already been taken')],
            'emailTrim' => ['email', 'trim'],

            //mobile rules
            'mobileRequired' => ['mobile', 'required', 'on' => ['mobile_register']],
            'mobilePattern' => ['mobile', 'match', 'pattern' => static::$mobileRegexp],
            'mobileLength' => ['mobile', 'string', 'max' => 11],
            'mobileUnique' => ['mobile', 'unique', 'message' => Yii::t('user', 'This mobile address has already been taken')],
            //'mobileTrim' => ['mobile', 'trim'],

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
     * 返回用户是否已经激活
     * @return boolean Whether the user is confirmed or not.
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at != null;
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
     * 获取auth_key
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * 返回用户详细资料
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id']);
    }

    /**
     * 返回用户微信资料
     * @return \yii\db\ActiveQuery
     */
    public function getWechat()
    {
        return $this->hasOne(Wechat::className(), ['user_id' => 'id']);
    }

    /**
     * @param Profile $profile
     */
    public function setProfile(Profile $profile)
    {
        $this->_profile = $profile;
    }

    /**
     * 返回用户附加资料
     * @return \yii\db\ActiveQuery
     */
    public function getUserData()
    {
        return $this->hasOne(Data::className(), ['user_id' => 'id']);
    }

    /**
     * 返回用户用户认证信息
     * @return \yii\db\ActiveQuery
     */
    public function getAuthentication()
    {
        return $this->hasOne(Authentication::className(), ['user_id' => 'id']);
    }

    /**
     * @param Data $data
     */
    public function setUserData(Data $data)
    {
        $this->_userData = $data;
    }

    /**
     * 定义延伸资料关系
     * @return ActiveQuery
     */
    public function getExtend(){
        return $this->hasOne(Extend::className(), ['user_id' => 'id']);
    }

    /**
     * 获取用户关注的Tag
     */
    public function getTags()
    {
        return $this->hasMany(Tag::className(), ['id' => 'tag_id'])->viaTable('{{%user_tag}}', ['user_id' => 'id']);
    }

    /**
     * 返回所有已经连接的社交媒体账户
     * @return Social[] Connected accounts ($provider => $account)
     */
    public function getAccounts()
    {
        $connected = [];
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
     * @return \yii\db\ActiveQuery 返回所有已添加的教育经历
     */
    public function getEducations()
    {
        return $this->hasMany(Education::className(), ['user_id' => 'id']);
    }

    /**
     * 返回所有已添加的工作经历
     * @return \yii\db\ActiveQuery
     */
    public function getCareers()
    {
        return $this->hasMany(Career::className(), ['user_id' => 'id']);
    }

    /**
     * 获取登陆历史
     * @return \yii\db\ActiveQuery
     */
    public function getLoginHistories()
    {
        return $this->hasMany(LoginHistory::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我的收藏
     * 一对多关系
     */
    public function getCollections()
    {
        return $this->hasMany(Collection::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我的关注一对多关系
     */
    public function getAttentions()
    {
        return $this->hasMany(Attention::className(), ['user_id' => 'id']);
    }

    /**
     * 获取我关注
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFollowers()
    {
        return $this->hasMany(Follow::className(), ['user_id' => 'id'])->andOnCondition(['model' => get_class($this)]);
    }

    /**
     * 用户我的粉丝
     * @return \yii\db\ActiveQueryInterface
     */
    public function getFans()
    {
        return $this->hasMany(Attention::className(), ['model_id' => 'id'])->andOnCondition(['model' => get_class($this)]);
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
     * 定义IM 关系
     * @return null|ActiveQuery
     */
    public function getIm()
    {
        if (Yii::$app->hasModule('im')) {
            return $this->hasMany(\yuncms\im\models\Account::className(), ['user_id' => 'id']);
        } else {
            return null;
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

    /** @inheritdoc */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * 获取我的APP列表
     * 一对多关系
     * @return ActiveQuery
     */
    public function getRests()
    {
        return $this->hasMany(Rest::className(), ['user_id' => 'id']);
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
        $this->confirmed_at = time();
        $this->password = $this->password == null ? Password::generate(8) : $this->password;
        $this->slug = $this->slug == null ? Inflector::slug($this->name, '-') : $this->slug;
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
        if ($this->scenario == 'mobile_register') {
            $this->name = $this->mobile;
        }
        $this->confirmed_at = $this->module->enableConfirmation ? null : time();
        $this->password = $this->module->enableGeneratingPassword ? Password::generate(8) : $this->password;
        $this->slug = $this->slug == null ? Inflector::slug($this->name, '-') : $this->slug;

        $this->trigger(self::BEFORE_REGISTER);
        if (!$this->save()) {
            return false;
        }
        if ($this->module->enableConfirmation) {
            /** @var Token $token */
            $token = new Token(['type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
        } else {
            Yii::$app->user->login($this, $this->module->rememberFor);
        }
        if ($this->email) {
            $this->module->sendMessage($this->email, Yii::t('user', 'Welcome to {0}', Yii::$app->name), 'welcome', ['user' => $this, 'token' => isset($token) ? $token : null, 'module' => $this->module, 'showPassword' => false]);
        }
        $this->trigger(self::AFTER_REGISTER);
        return true;
    }

    /**
     * 电子邮件确认
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
            if (($success = $this->confirm())) {
                Yii::$app->user->login($this, $this->module->rememberFor);
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
                if ($this->module->emailChangeStrategy == Module::STRATEGY_SECURE) {
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
                if ($this->module->emailChangeStrategy == Module::STRATEGY_DEFAULT || ($this->flags & self::NEW_EMAIL_CONFIRMED && $this->flags & self::OLD_EMAIL_CONFIRMED)) {
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
                if ($this->module->mobileChangeStrategy == Module::STRATEGY_SECURE) {
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
                if ($this->module->mobileChangeStrategy == Module::STRATEGY_DEFAULT || ($this->flags & self::NEW_MOBILE_CONFIRMED && $this->flags & self::OLD_MOBILE_CONFIRMED)) {
                    $this->mobile = $this->unconfirmed_mobile;
                    $this->unconfirmed_mobile = null;
                    Yii::$app->session->setFlash('success', Yii::t('user', 'Your mobile address has been changed'));
                }
                $this->save(false);
            }
        }
    }

    /**
     * 设置用户已经验证
     */
    public function confirm()
    {
        return (bool)$this->updateAttributes(['confirmed_at' => time()]);
    }

    /**
     * 设置最后登录时间
     * @return void
     */
    public function resetLoginData()
    {
        $this->userData->updateAttributes(['login_at' => time()]);
        $this->userData->updateAttributes(['login_ip' => Yii::$app->request->userIP]);
        $this->userData->updateCounters(['login_num' => 1]);
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
     */
    public function block()
    {
        return (bool)$this->updateAttributes(['blocked_at' => time(), 'auth_key' => Yii::$app->security->generateRandomString()]);
    }

    /**
     * 解除用户锁定
     */
    public function unblock()
    {
        return (bool)$this->updateAttributes(['blocked_at' => null]);
    }

    /**
     * 使用email地址生成一个新的用户名字
     */
    public function generateName()
    {
        // try to use name part of email
        $this->name = explode('@', $this->email)[0];
        if ($this->validate(['name'])) {
            return $this->name;
        }

        // generate name like "user1", "user2", etc...
        while (!$this->validate(['name'])) {
            $row = (new Query())->from('{{%user}}')->select('MAX(id) as id')->one();
            $this->name = 'user' . ++$row['id'];
        }
        return $this->name;
    }

    /**
     * 使用email地址生成一个新的标识
     */
    public function generateSlug()
    {
        // try to use slug part of email
        $this->slug = explode('@', $this->email)[0];
        if ($this->validate(['slug'])) {
            return $this->slug;
        }
        // generate slug like "user1", "user2", etc...
        while (!$this->validate(['slug'])) {
            $row = (new Query())->from('{{%user}}')->select('MAX(id) as id')->one();
            $this->slug = 'slug' . ++$row['id'];
        }
        return $this->slug;
    }

    /**
     * 创建 "记住我" 身份验证Key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
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
            return $this->getModule()->getAvatarUrl($this->id) . $avatarFileName;
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
            if (Yii::getAlias('@webroot', false) && !file_exists(Yii::getAlias('@webroot/img/no_avatar_big.gif'))) {
                $baseUrl = UserAsset::register(Yii::$app->view)->baseUrl;
                return Url::to($baseUrl . $avatarUrl, true);
            } else {
                return '';
            }
        }
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
     * 是否实名认证
     * @return bool
     */
    public function isAuthentication()
    {
        if ($this->authentication && $this->authentication->status == Authentication::STATUS_AUTHENTICATED) {
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
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
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            if ($this->_profile == null) {
                $this->_profile = new Profile();
            }
            $this->_profile->link('user', $this);

            if ($this->_userData == null) {
                $this->_userData = new Data();
            }
            $this->_userData->link('user', $this);
        }
    }

    /**
     * 定义乐观锁
     * @return string
     */
//    public function optimisticLock()
//    {
//        return 'version';
//    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('Method "' . __CLASS__ . '::' . __METHOD__ . '" is not implemented.');
    }

    /**
     * 通过用户名或者用户登陆邮箱或手机号获取用户
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
     * 通过Slug获取用户
     * @param string $slug 用户标识
     * @return null|static
     */
    public static function findBySlug($slug)
    {
        return static::findOne(['slug' => $slug]);
    }
}
