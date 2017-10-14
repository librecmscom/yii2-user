<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108091324Create_user_career_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 工作经历表
         */
        $this->createTable('{{%user_career}}', [
            'id' => $this->primaryKey(11)->unsigned()->comment('ID'),
            'user_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'position' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'start_at' => $this->string(7)->notNull(),
            'end_at' => $this->string(7),
            'created_at' => $this->integer()->unsigned()->notNull()->comment('Created At'),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_career_history_ibfk_1}}', '{{%user_career}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_career}}');
    }
}
