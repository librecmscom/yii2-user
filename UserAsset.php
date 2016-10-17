<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user;

use yii\web\AssetBundle;

/**
 * TypeAheadAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class UserAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/yii2-user/assets';

	public $css = [
        'js/user.js',
    ];

    public $js = [
        'js/cropper.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];

}