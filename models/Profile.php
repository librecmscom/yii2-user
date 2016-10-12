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
 * @property int $gender 性别
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
            ['mobile', 'string', 'min' => 11, 'max' => 11],
            ['sex', 'default', 'value' => self::SEX_UNCONFIRMED],
            ['sex', 'in', 'range' => [self::SEX_MALE, self::SEX_FEMALE, self::SEX_UNCONFIRMED]],
            ['public_email', 'email'],
            ['website', 'url'],
            ['address', 'string'],
            ['introduction', 'string'],
            [['public_email', 'nickname', 'timezone', 'location', 'website'], 'string', 'max' => 255],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => Yii::t('user', 'Name'),
            'sex' => Yii::t('user', 'Sex'),
            'mobile' => Yii::t('user', 'Mobile'),
            'public_email' => Yii::t('user', 'Email (public)'),
            'location' => Yii::t('user', 'Location'),
            'address' => Yii::t('user', 'Address'),
            'website' => Yii::t('user', 'Website'),
            'timezone' => Yii::t('user', 'Time zone'),
            'bio' => Yii::t('user', 'Bio'),
            'introduction' => Yii::t('user', 'Introduction'),
        ];
    }

    /**
     * 获取性别的字符串标识
     */
    public function getSexName()
    {
        switch ($this->gender) {
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
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
