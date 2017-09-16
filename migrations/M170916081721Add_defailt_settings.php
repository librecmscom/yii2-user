<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

/**
 * Class M170916081721Add_defailt_settings
 */
class M170916081721Add_defailt_settings extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "M170916081721Add_defailt_settings cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170916081721Add_defailt_settings cannot be reverted.\n";

        return false;
    }
    */
}
