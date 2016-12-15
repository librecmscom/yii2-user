<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\user\ModuleTrait;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id 用户ID
 * @property string $name
 * @property int $sex 性别
 * @property string $public_email
 * @property string $location
 * @property string $website
 * @property string $bio
 * @property string $avatar 头像Url
 * @property User $user
 */
class Profile extends ActiveRecord
{
    use ModuleTrait;

    // 未选择
    const SEX_UNCONFIRMED = 0;
    // 男
    const SEX_MALE = 1;
    // 女
    const SEX_FEMALE = 2;

    public $sexName;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['timezone', 'validateTimeZone'],
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['sex', 'default', 'value' => self::SEX_UNCONFIRMED],
            ['sex', 'in', 'range' => [self::SEX_MALE, self::SEX_FEMALE, self::SEX_UNCONFIRMED]],
            ['public_email', 'email'],
            ['website', 'url'],
            ['address', 'string'],
            ['introduction', 'string'],
            ['bio', 'string'],
            [['public_email', 'nickname', 'timezone','country', 'location', 'website'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nickname' => Yii::t('user', 'Nickname'),
            'sex' => Yii::t('user', 'Sex'),
            'mobile' => Yii::t('user', 'Mobile'),
            'public_email' => Yii::t('user', 'Email (public)'),
            'country' => Yii::t('user', 'Country'),
            'location' => Yii::t('user', 'Location'),
            'address' => Yii::t('user', 'Address'),
            'website' => Yii::t('user', 'Website'),
            'timezone' => Yii::t('user', 'Time zone'),
            'introduction' => Yii::t('user', 'Introduction'),
            'bio' => Yii::t('user', 'Bio'),
        ];
    }

    /**
     * 获取性别的字符串标识
     */
    public function getSexName()
    {
        switch ($this->sex) {
            case self::SEX_UNCONFIRMED:
                $genderName = Yii::t('user', 'Secrecy');
                break;
            case self::SEX_MALE:
                $genderName = Yii::t('user', 'Male');
                break;
            case self::SEX_FEMALE:
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
        $this->sexName = $this->getSexName();
    }
}
