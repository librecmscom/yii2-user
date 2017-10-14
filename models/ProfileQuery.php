<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * Class ProfileQuery
 * @package yuncms\user\models
 */
class ProfileQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     * @return Profile[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Profile|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * 只看妹子
     * @return $this
     */
    public function female()
    {
        return $this->andWhere(['gender' => Profile::GENDER_FEMALE]);
    }

}