<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108090930Create_user_login_history_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 登陆历史表
         */
        $this->createTable('{{%user_login_history}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer()->notNull()->comment('用户ID'),
            'ip' => $this->string()->notNull()->comment('登录IP'),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('时间'),
        ], $tableOptions);
        $this->addForeignKey('{{%user_login_history_ibfk_1}}', '{{%user_login_history}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_login_history}}');
    }
}
