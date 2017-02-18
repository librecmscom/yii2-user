<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170218040422Add_backend_menu extends Migration
{
    public function up()
    {
        $this->insert('{{%admin_menu}}', [
            'name' => '实名认证',
            'parent' => 5,
            'route' => '/user/authentication/index',
            'icon' => 'fa fa-id-card',
            'sort' => NULL,
            'data' => NULL
        ]);

        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '实名认证', 'parent' => 5])->scalar($this->getDb());
        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [

            ['认证查看', $id, '/user/authentication/view', 0, NULL],
            ['审核认证', $id, '/user/authentication/update', 0, NULL],
        ]);
    }

    public function down()
    {
        $id = (new \yii\db\Query())->select(['id'])->from('{{%admin_menu}}')->where(['name' => '实名认证', 'parent' => 5])->scalar($this->getDb());
        $this->delete('{{%admin_menu}}', ['parent' => $id]);
        $this->delete('{{%admin_menu}}', ['id' => $id]);
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
