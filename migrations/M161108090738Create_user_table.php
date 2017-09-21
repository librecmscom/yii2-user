<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108090738Create_user_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=100000';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11)->comment('用户id'),
            'username' => $this->string(50)->notNull()->unique()->comment('用户名用户标识'),
            'email' => $this->string()->unique()->comment('邮箱'),
            'mobile' => $this->string(11)->unique()->comment('手机号'),
            'nickname' => $this->string()->notNull()->comment('昵称'),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'avatar' => $this->boolean()->defaultValue(false),
            'unconfirmed_email' => $this->string(150),
            'unconfirmed_mobile' => $this->string(11),
            'blocked_at' => $this->integer()->unsigned(),
            'registration_ip' => $this->string(),
            'flags' => $this->integer()->defaultValue(0),
            'email_confirmed_at' => $this->integer()->unsigned(),
            'mobile_confirmed_at' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
