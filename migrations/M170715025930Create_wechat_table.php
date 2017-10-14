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
            'id' => $this->primaryKey(11)->unsigned()->comment('ID'),
            'user_id' => $this->integer()->unsigned()->comment('User ID'),
            'openid' => $this->string(64)->notNull()->comment('Open Id'),
            'unionid' => $this->string(64)->comment('Union ID'),
            'access_token' => $this->string()->comment('Access Token'),
            'expires_in' => $this->integer(6)->comment('Expires In'),
            'refresh_token' => $this->string()->comment('Refresh Token'),
            'scope' => $this->string(50)->comment('Scope'),
            //以下是用户资料
            'nickname' => $this->string(100)->comment('Nickname'),
            'sex' => $this->smallInteger(1)->comment('Gender'),
            'language' => $this->string(10)->comment('Language'),
            'country' => $this->string(3)->comment('Country'),
            'city' => $this->string(50)->comment('City'),
            'province' => $this->string(50)->comment('省份'),
            'headimgurl' => $this->string()->comment('Face Url'),
            //功能字段
            'data' => $this->text()->comment('Data'),
            'code' => $this->string(32)->unique()->comment('Code'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
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
