<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161108090824Create_user_profile_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }
        /**
         * 创建用户资料表
         */
        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->integer()->notNull(),
            'nickname' => $this->string(),
            'gender' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'mobile' => $this->string(),
            'public_email' => $this->string(),
            'country' => $this->string()->comment('国家'),
            'province' => $this->string()->comment('省'),
            'city' => $this->string()->comment('城市'),
            'location' => $this->string(),
            'address' => $this->string(),
            'website' => $this->string(),
            'timezone' => $this->string(100),//默认格林威治时间
            'introduction' => $this->string(),
            'bio' => $this->text()
        ], $tableOptions);
        $this->addPrimaryKey('{{%user_profile}}', '{{%user_profile}}', 'user_id');
        $this->addForeignKey('{{%user_profile_ibfk_1}}', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');

        $this->insert('{{%user_profile}}', [
            'user_id' => 1,
            'nickname' => '方圆百里找对手',
            'website'=>'https://www.l68.net',
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_profile}}');
    }
}
