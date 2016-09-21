<?php

use yii\db\Migration;

class m160921_065123_add_backend_menu extends Migration
{
    public function up()
    {
        $this->insert('{{%admin_menu}}', [
            'id' => 50,
            'name' => '用户管理',
            'parent' => 5,
            'route' => '/user/user/index', 'icon' => 'fa-user', 'sort' => null, 'data' => null]);

        $this->batchInsert('{{%admin_menu}}', ['name', 'parent', 'route', 'visible', 'sort'], [
            ['用户查看', 50, '/user/user/view', 0, NULL],
            ['创建用户', 50, '/user/user/create', 0, NULL],
            ['更新用户', 50, '/user/user/update', 0, NULL],
            ['用户设置', 50, '/user/user/setting', 0, NULL],
        ]);
    }

    public function down()
    {
        $this->delete('{{%admin_menu}}', ['id' => 50,]);
        $this->delete('{{%admin_menu}}', ['parent' => 50,]);
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
