<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108091432Create_notifcation_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_notifcation}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'to_user_id' => $this->integer(),
            'type' => $this->string(),
            'subject' => $this->string(),
            'source_id' => $this->integer(),
            'refer_content' => $this->string(),
            'status' => $this->integer(2),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_notifcation_ibfk_1}}', '{{%user_notifcation}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%user_notifcation_ibfk_2}}', '{{%user_notifcation}}', 'to_user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_notifcation}}');
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
