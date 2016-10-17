<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;

//use yuncms\system\models\File;
//use yuncms\system\services\FileObject;

/**
 * Class PortraitForm
 * @package yuncms\user
 */
class AvatarForm extends Model
{
    /**
     * @var \yii\web\UploadedFile 头像上传字段
     */
    public $file;

    public $x;

    public $y;

    /**
     * @var int 宽度
     */
    public $width;

    /**
     * @var int 高度
     */
    public $height;

    /** @var User */
    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['x', 'y', 'width', 'height'], 'required'],
            [['x', 'y', 'width', 'height'], 'integer'],
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'gif, jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('app', 'File has to be smaller than 2MB')],
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
    public function save()
    {
        if ($this->validate()) {
            $user = $this->getUser();

            /** @var \yii\system\Module $module */
            $module = Yii::$app->getModule('system');
            if ($module->saveAvatar($user->id, $this->portrait->tempName)) {
                $user->avatar = true;
                $user->save();
                return true;
            }
        }
        return false;
    }

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    public function beforeValidate()
    {
        $this->file = UploadedFile::getInstance($this, 'file');
        return parent::beforeValidate();
    }
}