<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id 用户ID
 * @property int $gender 性别
 * @property string $mobile 手机号
 * @property string $email 公开邮箱
 * @property string $country 国家
 * @property string $province 省份
 * @property string $city 城市
 * @property string $location 位置
 * @property string $address 地址街道
 * @property string $website 个人主页
 * @property string $timezone 时区
 * @property string $introduction 个人说明/简介
 * @property string $bio 个性签名
 * @property string $birthday 生日
 * @property string $current 当前状态
 * @property string $qq QQ号码
 * @property string $weibo 微博账户
 * @property string $wechat 微信账户
 * @property string $facebook facebook账户
 * @property string $twitter twitter账户
 * @property string $company 工作单位
 * @property string $company_job 职位
 * @property string $school 毕业院校
 *
 * @property-read string $greenName 性别
 * @property-read User $user
 */
class Profile extends ActiveRecord
{
    // 性别
    const GENDER_UNCONFIRMED = 0b0;
    const GENDER_MALE = 0b1;
    const GENDER_FEMALE = 0b10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    public static function find()
    {
        return new ProfileQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['gender', 'default', 'value' => self::GENDER_UNCONFIRMED],
            ['gender', 'in', 'range' => [self::GENDER_MALE, self::GENDER_FEMALE, self::GENDER_UNCONFIRMED]],

            ['mobile', 'match', 'pattern' => User::$mobileRegexp],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['email', 'email', 'checkDNS' => true],

            ['email', 'trim'],
            ['bio', 'string'],
            ['birthday', 'date', 'format' => 'php:Y-m-d', 'min' => '1900-01-01', 'max' => date('Y-m-d')],
            ['birthday', 'string', 'max' => 15],
            ['website', 'url'],
            ['timezone', 'validateTimeZone'],
            ['qq', 'integer', 'min' => 10001, 'max' => 9999999999],
            [['weibo', 'wechat', 'facebook', 'twitter',], 'string', 'max' => 50],

            [['email', 'country', 'province', 'city', 'location', 'address', 'website', 'introduction', 'company', 'company_job', 'school',], 'string', 'max' => 255],

            //['current', 'integer'],
            //['current', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gender' => Yii::t('user', 'Gender'),
            'genderName' => Yii::t('user', 'Gender'),
            'mobile' => Yii::t('user', 'Mobile'),
            'email' => Yii::t('user', 'Email (public)'),
            'country' => Yii::t('user', 'Country'),
            'location' => Yii::t('user', 'Location'),
            'address' => Yii::t('user', 'Address'),
            'website' => Yii::t('user', 'Website'),
            'timezone' => Yii::t('user', 'Time zone'),
            'introduction' => Yii::t('user', 'Introduction'),
            'bio' => Yii::t('user', 'Bio'),
            'birthday' => Yii::t('user', 'Birthday'),
            'current' => Yii::t('user', 'Current'),
            'qq' => Yii::t('user', 'QQ'),
            'weibo' => Yii::t('user', 'Weibo'),
            'wechat' => Yii::t('user', 'WeChat'),
            'facebook' => Yii::t('user', 'Facebook'),
            'twitter' => Yii::t('user', 'Twitter'),
            'company' => Yii::t('user', 'Company'),
            'company_job' => Yii::t('user', 'Company Job'),
            'school' => Yii::t('user', 'Company Job'),
        ];
    }

    /**
     * 获取性别的字符串标识
     */
    public function getGenderName()
    {
        switch ($this->gender) {
            case self::GENDER_UNCONFIRMED:
                $genderName = Yii::t('user', 'Secrecy');
                break;
            case self::GENDER_MALE:
                $genderName = Yii::t('user', 'Male');
                break;
            case self::GENDER_FEMALE:
                $genderName = Yii::t('user', 'Female');
                break;
            default:
                throw new \RuntimeException('Your database is not supported!');
        }
        return $genderName;
    }

    /**
     * 验证时区
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return \DateTimeZone
     */
    public function getTimeZone()
    {
        try {
            return new \DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new \DateTimeZone(Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param \DateTimeZone $timezone the timezone to save to the user's profile
     */
    public function setTimeZone(\DateTimeZone $timeZone)
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param \DateTime the datetime to convert
     * @return \DateTime
     */
    public function toLocalTime(\DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new \DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getExtend()
    {
        return $this->hasOne(Extend::className(), ['user_id' => 'user_id']);
    }

    /**
     * 是否是自己
     * @return bool
     */
    public function isMe()
    {
        return $this->user_id == Yii::$app->user->id;
    }
}
