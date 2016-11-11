<?php
/**
 * @link http://www.tintsoft.com/
 * @copyright Copyright (c) 2012 TintSoft Technology Co. Ltd.
 * @license http://www.tintsoft.com/license/
 */
namespace yuncms\user\models;

use Yii;
use yii\db\ActiveRecord;
use yuncms\user\ModuleTrait;

/**
 * This is the model class for table "user_data".
 *
 * @property int $user_id 用户ID
 * @property int $views
 * @property int $followers 关注
 * @property int $fans 粉丝数量
 * @property int $last_visit 最后访问时间
 * @property string $login_ip 登录IP
 * @property User $user 用户模型实例
 */
class Data extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user_data}}';
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

}