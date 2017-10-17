<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M170208041520Create_user_tag_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%user_tag}}', [
            'user_id' => $this->integer()->unsigned()->notNull()->comment('User Id'),
            'tag_id' => $this->integer()->unsigned()->notNull()->comment('Tag Id'),
        ], $tableOptions);
        $this->addPrimaryKey('', '{{%user_tag}}', ['user_id', 'tag_id']);
        $this->addForeignKey('user_tag_ibfk_1', '{{%user_tag}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('user_tag_ibfk_2', '{{%user_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%user_tag}}');
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
