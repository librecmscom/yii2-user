<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170715025930Create_wechat_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_wechat}}', [
            'id' => $this->primaryKey(11)->comment('主键'),
            'user_id' => $this->integer()->comment('已经绑定的用户ID'),
            'openid' => $this->string(64)->notNull()->comment('openid'),
            'unionid' => $this->string(64)->comment('联合ID'),
            'access_token' => $this->string()->comment('访问令牌'),
            'expires_in' => $this->integer(6)->comment('过期时间'),
            'refresh_token' => $this->string()->comment('刷新令牌'),
            'scope' => $this->string(50)->comment('Scope'),
            //以下是用户资料
            'nickname' => $this->string(100)->comment('昵称'),
            'sex' => $this->smallInteger(1)->comment('性别'),
            'language' => $this->string(10)->comment('语言'),
            'country' => $this->string(3)->comment('国家'),
            'city' => $this->string(50)->comment('城市'),
            'province' => $this->string(50)->comment('省份'),
            'headimgurl' => $this->string()->comment('头像Url'),
            //功能字段
            'data' => $this->text()->comment('原始数据'),
            'code' => $this->string(32)->unique(),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('创建时间'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('更新时间'),
        ], $tableOptions);
        $this->addForeignKey('{{%user_wechat_ibfk_1}}', '{{%user_wechat}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

    }

    public function safeDown()
    {
        $this->dropTable('{{%user_wechat}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170715025930Create_wechat_table cannot be reverted.\n";

        return false;
    }
    */
}
