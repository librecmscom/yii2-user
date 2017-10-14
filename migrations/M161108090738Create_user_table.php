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
            'id' => $this->primaryKey(11)->comment('ID'),
            'username' => $this->string(50)->notNull()->unique()->comment('Username'),
            'email' => $this->string()->unique()->comment('Email'),
            'mobile' => $this->string(11)->unique()->comment('Mobile'),
            'nickname' => $this->string()->notNull()->comment('Nickname'),
            'auth_key' => $this->string()->notNull()->comment('Auth Key'),
            'password_hash' => $this->string()->notNull()->comment('Password Hash'),
            'avatar' => $this->boolean()->defaultValue(false)->comment('Avatar'),
            'unconfirmed_email' => $this->string(150)->comment('Unconfirmed Email'),
            'unconfirmed_mobile' => $this->string(11)->comment('Unconfirmed Mobile'),
            'registration_ip' => $this->string()->comment('Registration Ip'),
            'flags' => $this->integer()->defaultValue(0)->comment('Flags'),
            'email_confirmed_at' => $this->integer()->unsigned()->comment('Email Confirmed At'),
            'mobile_confirmed_at' => $this->integer()->unsigned()->comment('Mobile Confirmed At'),
            'blocked_at' => $this->integer()->unsigned()->comment('Blocked At'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull()->comment('Updated At'),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
