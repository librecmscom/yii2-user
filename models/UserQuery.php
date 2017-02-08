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
}