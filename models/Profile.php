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
 * @property string $public_email 公开邮箱
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $timezone 时区
 *
 *
 * @property-read string $greenName 性别
 * @property-read User $user
 */
class Profile extends ActiveRecord
{
    // 未选择
    const GENDER_UNCONFIRMED = 0b0;

    // 男
    const GENDER_MALE = 0b1;

    // 女
    const GENDER_FEMALE = 0b10;

    /**
     * @var string 性别字符串
     */
    public $genderName;

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

            [['email', 'country', 'province', 'city', 'location', 'address', 'website', 'introduction', 'company', 'company_job',], 'string', 'max' => 255],

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
     * Validates the timezone attribute.
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
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
            return new \DateTimeZone(\Yii::$app->timeZone);
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
     * 是否是自己
     * @return bool
     */
    public function isMe()
    {
        return $this->user_id == Yii::$app->user->id;
    }

    /**
     * This method is called when the AR object is created and populated with the query result.
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->genderName = $this->getGenderName();
    }
}
