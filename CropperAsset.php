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
class CropperAsset extends AssetBundle
{
    public $sourcePath = '@vendor/yuncms/yii2-user/assets';

    public $css = [
        'css/cropper.css',
    ];

    public $js = [
        'js/cropper.js',
    ];

    public $depends = [
        'xutl\cropper\CropperAsset',
    ];

}