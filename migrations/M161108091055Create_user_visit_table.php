<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108091055Create_user_visit_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        /**
         * 用户访问历史表
         */
        $this->createTable('{{%user_visit}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->createIndex('visit_source_id_user_id_index', '{{%user_visit}}', ['user_id', 'source_id']);
        $this->addForeignKey('{{%user_visit_ibfk_1}}', '{{%user_visit}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%user_visit_ibfk_2}}', '{{%user_visit}}', 'source_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_visit}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
