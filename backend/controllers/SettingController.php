<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\backend\controllers;

use Yii;
use yii\Web\Controller;

/**
 * Class SettingController
 * @package yuncms\user
 */
class SettingController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'setting' => [
                'class' => 'backend\actions\SettingAction',
                'modelClass' => 'yuncms\user\backend\models\UserSettingForm'
            ],
        ];
    }
}