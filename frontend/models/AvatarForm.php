<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\frontend\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\web\UploadedFile;
use yuncms\user\models\User;
use yuncms\user\UserTrait;

/**
 * Class PortraitForm
 * @package yuncms\user
 */
class AvatarForm extends Model
{
    use UserTrait;

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
            [['x', 'y', 'width', 'height'], 'integer'],
            [['file'], 'required'],
            [['file'], 'file', 'extensions' => 'gif, jpg, png', 'maxSize' => 1024 * 1024 * 2, 'tooBig' => Yii::t('user', 'File has to be smaller than 2MB')],
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

            $avatarPath = $this->getAvatarPath($user->id);
            $originalImage = $avatarPath . '_avatar.jpg';
            //保存原图
            Image::crop($this->file->tempName, $this->width, $this->height, [$this->x, $this->y])->save($originalImage, ['quality' => 100]);
            //缩放
            Image::thumbnail($originalImage, 200, 200)->save($avatarPath . '_avatar_big.jpg', ['quality' => 100]);
            Image::thumbnail($avatarPath . '_avatar_big.jpg', 128, 128)->save($avatarPath . '_avatar_middle.jpg', ['quality' => 100]);
            Image::thumbnail($avatarPath . '_avatar_big.jpg', 48, 48)->save($avatarPath . '_avatar_small.jpg', ['quality' => 100]);
            $user->avatar = true;
            $user->save();
            return true;
        }
        return false;
    }

    /*
     * @return User
     */
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