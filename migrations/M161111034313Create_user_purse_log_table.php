<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161111034313Create_user_purse_log_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_purse_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'currency' => $this->string(50),
            'type' => $this->integer(1),
            'action' => $this->string(),
            'value' => $this->decimal(10, 2),
            'source_id' => $this->integer(),
            'source_type' => $this->string(),
            'subject' => $this->string(),
            'content' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_purse_log_ibfk_1}}', '{{%user_purse_log}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_purse_log}}');
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
