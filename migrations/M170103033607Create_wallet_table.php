<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

/**
 * Class M170103033607Create_wallet_table
 * @package yuncms
 */
class M170103033607Create_wallet_table extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_wallet}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'currency' => $this->string(10)->notNull(),
            'money' => $this->decimal(10, 2)->defaultValue(0.00),
            //'ver' => $this->bigInteger()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%user_wallet_log}}', [
            'id' => $this->primaryKey()->comment('自增ID'),
            'wallet_id' => $this->integer()->notNull()->comment('钱包ID'),
            'currency' => $this->string(10)->notNull(),
            'type' => $this->boolean()->defaultValue(false),
            'money' => $this->decimal(10, 2)->defaultValue(0.00)->comment('交易金额'),
            'action' => $this->string(),
            'msg' => $this->string(),
            'created_at' => $this->integer()->notNull()
        ], $tableOptions);

    }

    public function safeDown()
    {
        $this->dropTable('{{%user_wallet_log}}');
        $this->dropTable('{{%user_wallet}}');
    }

}
