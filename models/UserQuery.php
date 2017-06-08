<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use yii\db\ActiveQuery;
use yuncms\tag\behaviors\TagQueryBehavior;

/**
 * Class UserQuery
 * @package yuncms\user\models
 */
class UserQuery extends ActiveQuery
{

    /**
     * 关联tag搜索
     * @return array
     */
    public function behaviors()
    {
        return [
            TagQueryBehavior::className(),
        ];
    }

    /**
     * Gets entities by any tags.
     * @param string|string[] $value
     * @return ActiveQuery the owner
     */
    public function name($value)
    {
        $model = new User();
        $this->innerJoinWith('profile', false)
            ->andWhere(Profile::tableName() . ".`nickname` like '%{$value}%'")
            ->addGroupBy(array_map(function ($pk) use ($model) {
                return User::tableName() . '.' . $pk;
            }, $model->primaryKey()));
        return $this;
    }

    /**
     * 只看妹子
     * @return $this
     */
    public function female()
    {
        return $this->andWhere([Profile::tableName() . '.gender' => Profile::GENDER_FEMALE]);
    }

    /**
     * 查询今日注册
     * @return $this
     */
    public function DayRegister()
    {
        return $this->andWhere('date(created_at)=date(now())');
    }

    /**
     * 查询本周注册
     * @return $this
     */
    public function weekRegister()
    {
        return $this->andWhere('month(FROM_UNIXTIME(created_at)) = month(curdate()) AND week(FROM_UNIXTIME(created_at)) = week(curdate())');
    }

    /**
     * 查询本月注册
     * @return $this
     */
    public function monthRegister()
    {
        return $this->andWhere('month(FROM_UNIXTIME(created_at)) = month(curdate()) AND year(FROM_UNIXTIME(created_at)) = year(curdate())');
    }

    /**
     * 查询本年注册
     * @return $this
     */
    public function yearRegister()
    {
        return $this->andWhere('year(FROM_UNIXTIME(created_at)) = year(curdate())');
    }

    /**
     * 查询本季度注册
     * @return $this
     */
    public function quarterRegister()
    {
        return $this->andWhere('quarter(FROM_UNIXTIME(created_at)) = quarter(curdate())');
    }
}