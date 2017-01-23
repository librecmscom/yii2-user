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
            'coins' => $this->integer()->unsigned()->defaultValue(0)->comment('金币'),
            'credits' => $this->integer()->unsigned()->defaultValue(0)->comment('信用'),
            'login_ip' => $this->string()->comment('登录IP'),
            'login_at' => $this->integer()->unsigned()->comment('登录事件'),
            'login_num' => $this->integer()->unsigned()->defaultValue(0)->comment('登录次数'),
            'views' => $this->integer()->unsigned()->defaultValue(0)->comment('查看数数'),
            'supports' => $this->integer()->unsigned()->defaultValue(0)->comment('赞数'),
            'followers' => $this->integer()->unsigned()->defaultValue(0)->comment('关注数'),
            'last_visit' => $this->integer()->unsigned()->comment('最后活动'),
        ], $tableOptions);
        $this->addPrimaryKey('{{%user_data}}','{{%user_data}}','user_id');
        $this->addForeignKey('{{%user_data_ibfk_1}}', '{{%user_data}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->insert('{{%user_data}}', [
            'user_id' => 1,
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_data}}');
    }
}
