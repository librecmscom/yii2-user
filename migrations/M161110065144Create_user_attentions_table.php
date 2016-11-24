<?php

namespace yuncms\user\migrations;

use yii\db\Migration;

class M161110065144Create_user_attentions_table extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /**
         * 用户关注表
         */
        $this->createTable('{{%user_attentions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'model_id' => $this->integer()->notNull(),
            'model' => $this->string()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_attentions_ibfk_1}}', '{{%user_attentions}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->createIndex('attentions_source_id_source_type_index', '{{%user_attentions}}', ['model_id', 'model'], false);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_attentions}}');
    }
}
