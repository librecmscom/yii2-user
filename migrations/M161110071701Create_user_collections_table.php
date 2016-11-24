<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161110071701Create_user_collections_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 用户收藏表
         */
        $this->createTable('{{%user_collections}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'model' => $this->string()->notNull(),
            'subject' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_collections_ibfk_1}}', '{{%user_collections}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('collections_source_id_source_type_index', '{{%user_collections}}', ['model_id', 'model'], false);
    }

    public function down()
    {
        $this->dropTable('{{%user_collections}}');
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
