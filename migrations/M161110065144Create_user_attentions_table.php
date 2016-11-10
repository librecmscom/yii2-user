<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161110065144Create_user_attentions_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_attentions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'source_type' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_attentions_ibfk_1}}', '{{%user_attentions}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('attentions_source_id_source_type_index', '{{%user_attentions}}', ['source_id', 'source_type'], false);
    }

    public function down()
    {
        $this->dropTable('{{%user_attentions}}');
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