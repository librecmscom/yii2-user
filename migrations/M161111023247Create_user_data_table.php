<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161111023247Create_user_data_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        /**
         * 创建用户资料表
         */
        $this->createTable('{{%user_data}}', [
            'user_id' => $this->integer()->notNull() . ' PRIMARY KEY',
            'amount' => $this->decimal(10, 2)->defaultValue('0.00'),
            'point' => $this->integer()->defaultValue(0),
            'login_at' => $this->integer()->unsigned(),
            'login_num' => $this->integer()->unsigned()->defaultValue(0),
            'views' => $this->integer()->unsigned()->defaultValue(0),
            'supports' => $this->integer()->unsigned()->defaultValue(0),
            'followers' => $this->integer()->unsigned()->defaultValue(0),
            'last_visit' => $this->integer()->unsigned(),
            'login_ip' => $this->string()
        ], $tableOptions);
        $this->addForeignKey('{{%user_data_ibfk_1}}', '{{%user_data}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%user_data}}');
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
