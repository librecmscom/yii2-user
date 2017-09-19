<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108091015Create_user_social_account_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        /**
         * 创建社交账户表
         */
        $this->createTable('{{%user_social_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'username' => $this->string(),
            'email' => $this->string(),
            'provider' => $this->string(50)->notNull(),
            'client_id' => $this->string(100)->notNull(),
            'code' => $this->string(32)->unique(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'data' => $this->text(),
        ], $tableOptions);

        $this->createIndex('account_unique', '{{%user_social_account}}', ['provider', 'client_id'], true);
        $this->addForeignKey('{{%user_account_ibfk_1}}', '{{%user_social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_social_account}}');
    }
}
