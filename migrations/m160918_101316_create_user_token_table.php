<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_token`.
 */
class m160918_101316_create_user_token_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE  utf8mb4_general_ci ENGINE=InnoDB';
        }

        /**
         * 创建令牌表
         */
        $this->createTable('{{%user_token}}', [
            'user_id' => $this->integer()->notNull(),
            'code' => $this->string(32)->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'type' => $this->smallInteger()->notNull(),
        ], $tableOptions);

        $this->createIndex('token_unique', '{{%user_token}}', ['user_id', 'code', 'type'], true);
        $this->addForeignKey('{{%user_token_ibfk_1}}', '{{%user_token}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_token}}');
    }
}
