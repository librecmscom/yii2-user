<?php

namespace yuncms\migrations;

use yii\db\Migration;

/**
 * Class M170816074430Add_backend_menu
 */
class M170816074430Add_backend_menu extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '用户管理', 'parent' => 5])->scalar($this->getDb());

        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['用户设置', $id, '/user/user/settings', 0, NULL],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $parentId = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '用户管理', 'parent' => 5])->scalar($this->getDb());

        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '用户设置', 'parent' => $parentId])->scalar($this->getDb());

        $this->delete('{{%admin_menu}}', ['id' => $id, 'parent' => $parentId]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "M170816074430Add_backend_menu cannot be reverted.\n";

        return false;
    }
    */
}
