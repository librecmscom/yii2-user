<?php

namespace yuncms\user\migrations;

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
        $date = date('Y-m-d H:i:s');
        $this->batchInsert('{{%settings}}', ['type', 'section', 'key', 'value', 'active', 'created', 'modified'], [
            ['boolean', 'user', 'enableRegistration', '1', 1, $date, $date],
            ['boolean', 'user', 'enableMobileRegistration', '0', 1, $date, $date],
            ['boolean', 'user', 'enableRegistrationCaptcha', '0', 1, $date, $date],
            ['boolean', 'user', 'enableGeneratingPassword', '0', 1, $date, $date],
            ['boolean', 'user', 'enableConfirmation', '0', 1, $date, $date],
            ['boolean', 'user', 'enableUnconfirmedLogin', '0', 1,$date, $date],
            ['boolean', 'user', 'enablePasswordRecovery', '1', 1, $date, $date],
            ['integer', 'user', 'emailChangeStrategy', '1', 1, $date, $date],
            ['integer', 'user', 'mobileChangeStrategy', '1', 1, $date, $date],
            ['integer', 'user', 'rememberFor', '1209600', 1, $date, $date],
            ['integer', 'user', 'confirmWithin', '86400', 1, $date, $date],
            ['integer', 'user', 'recoverWithin', '21600', 1, $date, $date],
            ['integer', 'user', 'cost', '10', 1, $date, $date],
            ['string', 'user', 'avatarPath', '@webroot/uploads/avatar', 1, $date, $date],
            ['string', 'user', 'avatarUrl', '@web/uploads/avatar', 1, $date, $date],
        ]);

        //刷新设置缓存
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
