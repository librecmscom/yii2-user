<?php

namespace yuncms\user\jobs;

use yii\base\Object;
use yii\queue\RetryableJob;
use yuncms\user\models\Extend;

/**
 * 记录最后活动时间
 * @package yuncms\user\jobs
 */
class LastVisitJob extends Object implements RetryableJob
{
    /**
     * @var int user id
     */
    public $user_id;

    /**
     * @var int 最后活动时间
     */
    public $time;

    /**
     * @inheritdoc
     */
    public function execute($queue)
    {
        Extend::updateAll(['last_visit' => $this->time], ['user_id' => $this->user_id]);
    }

    /**
     * @inheritdoc
     */
    public function getTtr()
    {
        return 60;
    }

    /**
     * @inheritdoc
     */
    public function canRetry($attempt, $error)
    {
        return $attempt < 3;
    }
}
