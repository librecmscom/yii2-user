<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\api\controllers;

use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yuncms\oauth2\Controller;

/**
 * Class AccountController
 * @package yuncms\user\api\controllers
 */
class AccountController extends Controller
{
    /**
     * 获取用户基本信息
     * @return array
     */
    public function actionIndex()
    {
        /** @var \yuncms\user\models\User $user */
        $user = Yii::$app->user->identity;
        return [
            'id' => $user->id,
            'nickname' => $user->profile->nickname,
            'username' => $user->username,
            'mobile' => $user->mobile,
            'email' => $user->email,
            'avatar' => $user->getAvatar(),
            'registration_ip' => $user->registration_ip,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }

    /**
     * 获取用户 Profile 信息
     * @return \yuncms\user\models\Profile
     */
    public function actionProfile()
    {
        /** @var \yuncms\user\models\User $user */
        $user = Yii::$app->user->identity;
        return $user->profile;
    }
}