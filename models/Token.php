<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yuncms\user\ModuleTrait;

/**
 * Token Active Record model.
 *
 * @property integer $user_id
 * @property string $code
 * @property integer $created_at
 * @property integer $type
 * @property string $url
 * @property bool $isExpired
 * @property User $user
 */
class Token extends ActiveRecord
{
    use ModuleTrait;

    const TYPE_CONFIRMATION = 0;
    const TYPE_RECOVERY = 1;
    const TYPE_CONFIRM_NEW_EMAIL = 2;
    const TYPE_CONFIRM_OLD_EMAIL = 3;
    const TYPE_CONFIRM_NEW_MOBILE = 4;
    const TYPE_CONFIRM_OLD_MOBILE = 5;

    protected $confirmWithin;

    public function init()
    {
        parent::init();
        $this->confirmWithin = Yii::$app->settings->get('confirmWithin', 'user');
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_token}}';
    }

    /**
     * @inheritdoc
     */
    public static function primaryKey()
    {
        return ['user_id', 'code', 'type'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Yii::$app->user->identityClass, ['id' => 'user_id']);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
                $route = '/user/registration/confirm';
                break;
            case self::TYPE_RECOVERY:
                $route = '/user/recovery/reset';
                break;
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $route = '/user/setting/confirm';
                break;
            case self::TYPE_CONFIRM_NEW_MOBILE:
            case self::TYPE_CONFIRM_OLD_MOBILE:
                $route = '/user/setting/mobile';
                break;
            default:
                throw new \RuntimeException();
        }

        return Url::to([$route, 'id' => $this->user_id, 'code' => $this->code], true);
    }

    /**
     * @return boolean Whether token has expired.
     */
    public function getIsExpired()
    {
        switch ($this->type) {
            case self::TYPE_CONFIRMATION:
            case self::TYPE_CONFIRM_NEW_EMAIL:
            case self::TYPE_CONFIRM_OLD_EMAIL:
                $expirationTime = $this->module->confirmWithin;
                break;
            case self::TYPE_CONFIRM_NEW_MOBILE:
            case self::TYPE_CONFIRM_OLD_MOBILE:
                $expirationTime = $this->module->confirmWithin;
                break;
            case self::TYPE_RECOVERY:
                $expirationTime = $this->module->recoverWithin;
                break;
            default:
                throw new \RuntimeException();
        }

        return ($this->created_at + $expirationTime) < time();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            static::deleteAll(['user_id' => $this->user_id, 'type' => $this->type]);
            $this->setAttribute('created_at', time());
            if ($this->type == self::TYPE_CONFIRM_NEW_MOBILE || $this->type == self::TYPE_CONFIRM_OLD_MOBILE) {
                $this->setAttribute('code', Yii::$app->security->generateSalt(6));
            } else {
                $this->setAttribute('code', Yii::$app->security->generateRandomString());
            }
        }
        return parent::beforeSave($insert);
    }
}
