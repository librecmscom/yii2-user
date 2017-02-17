<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170217041155Create_user_authentications_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_authentications}}', [
            'user_id' => $this->integer()->notNull(),
            'real_name' => $this->string(),
            'id_card' => $this->string(),
            'status' => $this->smallInteger(1)->defaultValue(0),
            'failed_reason' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%user_authentications}}', '{{%user_authentications}}', 'user_id');
        $this->addForeignKey('{{%user_authentications_ibfk_1}}', '{{%user_authentications}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_authentications}}');
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
