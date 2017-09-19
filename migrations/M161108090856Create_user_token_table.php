<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108090856Create_user_token_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        /**
         * 创建令牌表
         */
        $this->createTable('{{%user_token}}', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ], $tableOptions);

        $this->createIndex('token_unique', '{{%user_token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('{{%user_token_ibfk_1}}', '{{%user_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_token}}');
    }
}
