<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108091545Create_user_point_log_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_point_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'type' => $this->integer(1),
            'action' => $this->string(),
            'value' => $this->decimal(10, 2),
            'source_id' => $this->integer(),
            'source_type' => $this->string(),
            'subject' => $this->string(),
            'content' => $this->string(),
            'refer_id' => $this->integer(),
            'refer_user_id' => $this->integer(),
            'refer_content' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_point_log}}');
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
