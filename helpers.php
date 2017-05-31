<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
if (!function_exists('coin')) {
    /**
     * 金币变动
     * @param int $user_id
     * @param string $action
     * @param int $coins 金币数量
     * @param int $sourceId 源ID
     * @param null $sourceSubject 源标题
     * @return bool
     * @throws \yii\db\Exception
     */
    function coin($user_id, $action, $coins = 0, $sourceId = 0, $sourceSubject = null)
    {
        /** @var \yuncms\user\Module $user */
        $user = Yii::$app->getModule('user');
        return $user->coin($user_id, $action, $coins, $sourceId, $sourceSubject);
    }
}

if (!function_exists('credit')) {
    /**
     * 修改用户经验值
     * @param int $user_id 用户id
     * @param string $action 执行动作：提问、回答、发起文章
     * @param int $sourceId 源：问题id、回答id、文章id等
     * @param string $sourceSubject 源主题：问题标题、文章标题等
     * @param int $credits 经验值
     * @return bool  操作成功返回true 否则  false
     */
    function credit($user_id, $action, $credits = 0, $sourceId = 0, $sourceSubject = null)
    {
        /** @var \yuncms\user\Module $user */
        $user = Yii::$app->getModule('user');
        return $user->credit($user_id, $action, $credits, $sourceId, $sourceSubject);
    }
}

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