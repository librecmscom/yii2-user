<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161117091820Create_user_amounts_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_amounts}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'action' => $this->string(100)->notNull(),
            'source_id' => $this->integer()->notNull(),
            'source_subject' => $this->string()->notNull(),
            'coins' => $this->decimal(8, 2)->defaultValue('0.00')->notNull(),
            'credits' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_amounts}}');
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
