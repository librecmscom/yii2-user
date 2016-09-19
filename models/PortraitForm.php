<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
//use yuncms\system\models\File;
//use yuncms\system\services\FileObject;

/**
 * Class PortraitForm
 * @package yuncms\user
 */
class PortraitForm extends Model
{
    /**
     * @var \yii\web\UploadedFile 头像上传字段
     */
    public $portrait;

    /** @var Profile */
    private $_profile;

    /** @return User */
    public function getProfile()
    {
        if ($this->_profile == null) {
            $this->_profile = Yii::$app->user->identity->profile;
        }
        return $this->_profile;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['portrait'], 'required'],
            [['portrait'], 'file', 'extensions' => 'gif, jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('app', 'File has to be smaller than 2MB')],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'portrait' => Yii::t('user', 'Portrait'),
        ];
    }

    /**
     * 保存头像
     *
     * @return boolean
     */
    public function upload()
    {
        if ($this->validate()) {
            $profile = $this->getProfile();

            /** @var \yii\system\Module $module */
            $module = Yii::$app->getModule('system');
            if ($module->saveAvatar($profile->user_id, $this->portrait->tempName)) {
                $profile->avatar = true;
                $profile->save();
                return true;
            }
        }
        return false;
    }
}