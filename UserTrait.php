<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user;

use Yii;
use yii\helpers\FileHelper;

/**
 * Class UserTrait
 * @package yuncms\user
 */
trait UserTrait
{
    /**
     * 获取模块配置
     * @param string $key
     * @param null $default
     * @return bool|mixed|string
     */
    public function getSetting($key, $default = null)
    {
        $value = Yii::$app->settings->get($key, 'user', $default);
        if ($key == 'avatarUrl' || $key == 'avatarPath') {
            return Yii::getAlias($value);
        }
        return $value;
    }

    /**
     * 获取头像的存储路径
     * @param int $userId
     * @return string
     */
    public function getAvatarPath($userId)
    {
        $avatarPath = $this->getSetting('avatarPath') . '/' . $this->getAvatarSubPath($userId);
        if (!is_dir($avatarPath)) {
            FileHelper::createDirectory($avatarPath);
        }
        return $avatarPath . substr($userId, -2);
    }

    /**
     * 获取指定用户头像访问Url
     * @param int $userId 用户ID
     * @return string
     */
    public function getAvatarUrl($userId)
    {
        return $this->getSetting('avatarUrl') . '/' . $this->getAvatarSubPath($userId) . substr($userId, -2);
    }

    /**
     * 计算用户头像子路径
     *
     * @param int $userId 用户ID
     * @return string
     */
    public function getAvatarSubPath($userId)
    {
        $id = sprintf("%09d", $userId);
        $dir1 = substr($id, 0, 3);
        $dir2 = substr($id, 3, 2);
        $dir3 = substr($id, 5, 2);
        return $dir1 . '/' . $dir2 . '/' . $dir3 . '/';
    }
}