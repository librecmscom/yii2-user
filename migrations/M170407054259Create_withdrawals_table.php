<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170407054259Create_withdrawals_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_withdrawals}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'bankcard_id'=> $this->integer()->notNull()->comment('银行卡关系'),
            'currency' => $this->string(10)->notNull()->comment('币种'),
            'money' => $this->decimal(10, 2)->defaultValue(0.00),
            'status'=>$this->smallInteger(1)->defaultValue(0)->comment('状态'),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_withdrawals_ibfk_1}}', '{{%user_withdrawals}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%user_withdrawals_ibfk_2}}', '{{%user_withdrawals}}', 'bankcard_id', '{{%user_bankcard}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_withdrawals}}');
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
