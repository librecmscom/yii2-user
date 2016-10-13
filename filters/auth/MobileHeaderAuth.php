<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\filters\auth;

use yuncms\user\models\Rest;
use yii\filters\auth\AuthMethod;

/**
 * HttpHeaderAuth is an action filter that supports the authentication method based on HTTP Bearer token.
 *
 * You may use HttpHeaderAuth by attaching it as a behavior to a controller or module, like the following:
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'bearerAuth' => [
 *             'class' => \yuncms\system\filters\auth\ApiHeaderAuth::className(),
 *         ],
 *     ];
 * }
 * ```
 */
class MobileHeaderAuth extends AuthMethod
{
    /**
     * @var string the parameter name for passing the access id
     */
    public $idParam = 'access-id';

    /**
     * @var string the parameter name for passing the access token
     */
    public $tokenParam = 'access-token';

    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        $accessId = $request->getHeaders()->get($this->idParam);
        $accessToken = $request->getHeaders()->get($this->tokenParam);
        $token = $accessId . ',' . $accessToken;
        if (is_string($token)) {
            $identity = $user->loginByAccessToken($token, Rest::TYPE_MOBILE);
            if ($identity !== null) {
                return $identity;
            }
        }
        if ($token !== null) {
            $this->handleFailure($response);
        }

        return null;
    }
}