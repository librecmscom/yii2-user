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
 * Class ProfileQuery
 * @package yuncms\user\models
 */
class ProfileQuery extends ActiveQuery
{
    /**
     * Gets entities by any tags.
     * @param string|string[] $value
     * @return ActiveQuery the owner
     */
    public function name($value)
    {
        $model = new Profile();
        $this->innerJoinWith('user', false)
            ->andWhere(User::tableName().".`username` like '%{$value}%'")
            ->addGroupBy(array_map(function ($pk) use ($model) {
                return Profile::tableName() . '.' . $pk;
            }, $model->primaryKey()));
        return $this;
    }

    /**
     * 只看妹子
     * @return $this
     */
    public function female()
    {
        return $this->andWhere(['sex' => Profile::GENDER_FEMALE]);
    }
}