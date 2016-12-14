<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161111023247Create_user_data_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        /**
         * 创建用户附表
         */
        $this->createTable('{{%user_data}}', [
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(10, 2)->defaultValue('0.00')->comment('余额'),
            'coins' => $this->decimal(10, 2)->defaultValue('0.00')->comment('金币'),
            'credits' => $this->integer()->defaultValue(0)->comment('信用分'),
            'login_at' => $this->integer()->unsigned(),
            'login_num' => $this->integer()->unsigned()->defaultValue(0),
            'views' => $this->integer()->unsigned()->defaultValue(0),
            'supports' => $this->integer()->unsigned()->defaultValue(0),
            'followers' => $this->integer()->unsigned()->defaultValue(0),
            'last_visit' => $this->integer()->unsigned(),
            'login_ip' => $this->string()
        ], $tableOptions);
        $this->addPrimaryKey('{{%user_data}}','{{%user_data}}','user_id');
        $this->addForeignKey('{{%user_data_ibfk_1}}', '{{%user_data}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_data}}');
    }
}
