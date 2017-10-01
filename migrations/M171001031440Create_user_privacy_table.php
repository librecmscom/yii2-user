<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M171001031440Create_user_privacy_table extends Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_privacy}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->comment('用户ID'),
            'privacy' => $this->string(50)->comment('隐私项目'),
            'status' => $this->smallInteger(1)->defaultValue(0)->comment('状态0仅自己可见1好友可见2所有人可见')
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_privacy}}');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171001031440Create_user_privacy_table cannot be reverted.\n";

        return false;
    }
    */
}
