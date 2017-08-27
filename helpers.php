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

if (!function_exists('doing')) {
    /**
     * 记录用户动态
     * @param int $userId 动态发起人
     * @param string $action 动作 ['ask','answer',...]
     * @param string $sourceType 被引用的内容类型
     * @param int $sourceId 问题或文章ID
     * @param string $subject 问题或文章标题
     * @param string $content 回答或评论内容
     * @param int $referId 问题或者文章ID
     * @param int $referUserId 引用内容作者ID
     * @param null $referContent 引用内容
     * @return bool
     */
    function doing($userId, $action, $sourceType, $sourceId, $subject, $content = '', $referId = 0, $referUserId = 0, $referContent = null)
    {
        /** @var \yuncms\user\Module $user */
        $user = Yii::$app->getModule('user');
        return $user->doing($userId, $action, $sourceType, $sourceId, $subject, $content, $referId, $referUserId, $referContent);
    }
}