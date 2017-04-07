<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170407043127Create_bankcard_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_bankcard}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'bank' => $this->string()->comment('银行名称'),
            'bank_city' => $this->string()->comment('开户城市'),
            'bank_username' => $this->string()->comment('开户名'),
            'bank_name' => $this->string()->comment('开户行'),
            'bankcard_number' => $this->string()->comment('银行卡号'),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_bankcard_ibfk_1}}', '{{%user_bankcard}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_bankcard}}');
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
