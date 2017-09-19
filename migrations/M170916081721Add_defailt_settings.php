<?php

namespace yuncms\migrations;

use Yii;
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


        $this->batchInsert('{{%settings}}', ['type', 'section', 'key', 'value', 'active', 'created', 'modified'], [
            ['boolean', 'user', 'enableRegistration', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enableMobileRegistration', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enableRegistrationCaptcha', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enableGeneratingPassword', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enableConfirmation', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enableUnconfirmedLogin', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['boolean', 'user', 'enablePasswordRecovery', '0', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'emailChangeStrategy', '1', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'mobileChangeStrategy', '1', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'rememberFor', '1209600', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'confirmWithin', '86400', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'recoverWithin', '21600', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['integer', 'user', 'cost', '10', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['string', 'user', 'avatarPath', '@root/uploads/avatar', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
            ['string', 'user', 'avatarUrl', '@web/uploads/avatar', 1, date('Y-m-d H:i:s'), date('Y-m-d H:i:s')],
        ]);

        Yii::$app->settings->clearCache();
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->delete('{{%settings}}', ['section' => 'user']);
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
