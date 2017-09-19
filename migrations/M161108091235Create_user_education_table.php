<?php

namespace yuncms\migrations;

use yii\db\Migration;

class M161108091235Create_user_education_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        /**
         * 教育经历表
         */
        $this->createTable('{{%user_education}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer(),
            'school' => $this->string()->notNull(),
            'department' => $this->string()->notNull(),
            'degree' => $this->string()->notNull(),
            'date' => $this->string(6)->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_education_history_ibfk_1}}', '{{%user_education}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_education}}');
    }
}
