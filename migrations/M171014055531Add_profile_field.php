<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M171014055531Add_profile_field extends Migration
{

    public function safeUp()
    {
        $this->addColumn('{{%user_profile}}', 'school', $this->string()->comment('School'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%user_profile}}', 'school');
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M171014055531Add_profile_field cannot be reverted.\n";

        return false;
    }
    */
}
