<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
if (!function_exists('notify')) {
    /**
     * 发送用户通知
     * @param int $fromUserId
     * @param int $toUserId
     * @param string $type
     * @param string $subject
     * @param int $model_id
     * @param string $content
     * @param string $referType
     * @param int $refer_id
     * @return bool
     */
    function notify($fromUserId, $toUserId, $type, $subject = '', $model_id = 0, $content = '', $referType = '', $refer_id = 0)
    {
        /** @var \yuncms\user\Module $user */
        $user = Yii::$app->getModule('user');
        return $user->notify($fromUserId, $toUserId, $type, $subject, $model_id, $content, $referType, $refer_id);
    }
}