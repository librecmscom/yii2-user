<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use yii\imagine\Image;
use yuncms\user\ModuleTrait;

/**
 * Class PortraitForm
 * @package yuncms\user
 */
class AvatarForm extends Model
{
    use ModuleTrait;

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

            $avatarPath = Yii::getAlias('@uploads/avatar/' . $this->getModule()->getAvatarHome($user->id));
            if (!is_dir($avatarPath)) {
                FileHelper::createDirectory($avatarPath);
            }

            list($width, $height, $type, $attr) = getimagesize($this->file->tempName);

            if ($width < 200 && $height < 200) {
                file_put_contents($avatarPath . '/avatar_big.jpg', file_get_contents($this->file->tempName));
            } else {
                //按提交剪切
                if (!empty($this->x) && !empty($this->y)) {
                    Image::crop($this->file->tempName, 200, 200, [$this->x, $this->y])->save($avatarPath . '/avatar_big.jpg');
                } else {
                    Image::crop($this->file->tempName, 200, 200)->save($avatarPath . '/avatar_big.jpg', ['quality' => 100]);
                }
            }
            //缩放
            Image::thumbnail($avatarPath . '/avatar_big.jpg', 128, 128)->save($avatarPath . '/avatar_middle.jpg', ['quality' => 100]);

            Image::thumbnail($avatarPath . '/avatar_big.jpg', 48, 48)->save($avatarPath . '/avatar_small.jpg', ['quality' => 100]);
            $user->avatar = true;
            $user->save();
            return true;
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