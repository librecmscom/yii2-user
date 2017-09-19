<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\frontend\assets;

use yii\web\AssetBundle;

/**
 * TypeAheadAsset
 *
 * @author Alexander Kochetov <creocoder@gmail.com>
 */
class UserAsset extends AssetBundle
{
    public $sourcePath = '@yuncms/user/frontend/views/assets';

    public $css = [
        'css/user.css'
    ];

    public $js = [
        'js/user.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}