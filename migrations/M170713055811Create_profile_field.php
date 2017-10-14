<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170713055811Create_profile_field extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%user_profile}}', 'birthday', $this->string(15)->comment('Birthday'));
        $this->addColumn('{{%user_profile}}', 'current', $this->smallInteger(1)->comment('Current'));
        $this->addColumn('{{%user_profile}}', 'qq', $this->string(11)->comment('QQ'));
        $this->addColumn('{{%user_profile}}', 'weibo', $this->string(50)->comment('Weibo'));
        $this->addColumn('{{%user_profile}}', 'wechat', $this->string(50)->comment('Wechat'));
        $this->addColumn('{{%user_profile}}', 'facebook', $this->string(50)->comment('Facebook'));
        $this->addColumn('{{%user_profile}}', 'twitter', $this->string(50)->comment('Twitter'));
        $this->addColumn('{{%user_profile}}', 'company', $this->string()->comment('Company'));
        $this->addColumn('{{%user_profile}}', 'company_job', $this->string()->comment('Company Job'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_profile}}', 'birthday');
        $this->dropColumn('{{%user_profile}}', 'current');
        $this->dropColumn('{{%user_profile}}', 'qq');
        $this->dropColumn('{{%user_profile}}', 'weibo');
        $this->dropColumn('{{%user_profile}}', 'wechat');
        $this->dropColumn('{{%user_profile}}', 'facebook');
        $this->dropColumn('{{%user_profile}}', 'twitter');
        $this->dropColumn('{{%user_profile}}', 'company');
        $this->dropColumn('{{%user_profile}}', 'company_job');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170713055811Create_profile_field cannot be reverted.\n";

        return false;
    }
    */
}
