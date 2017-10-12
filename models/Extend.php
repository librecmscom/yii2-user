<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */

namespace yuncms\user\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "user_extend".
 *
 * @property int $user_id 用户ID
 * @property int $integrals 积分
 * @property int $coins 金币
 * @property int $credits 威望
 * @property int $views 主页访问量
 * @property int $followers 关注
 * @property int $fans 粉丝数量
 * @property int $last_visit 最后访问时间
 * @property string $login_ip 登录IP
 * @property int $login_at 登录时间
 * @property int $login_num 登录次数
 *
 * @property User $user 用户模型实例
 */
class Extend extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_extend}}';
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * 获取指定字段排行榜
     * @param string $field 字段
     * @param int $limit
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function top($field, $limit)
    {
        return static::find()->with('user')->orderBy([$field => SORT_DESC, 'last_visit' => SORT_DESC])->limit($limit)->all();
    }
}