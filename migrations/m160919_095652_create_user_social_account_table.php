<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_social_account`.
 */
class m160919_095652_create_user_social_account_table extends Migration
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
         * 创建社交账户表
         */
        $this->createTable('{{%user_social_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'username' => $this->string(),
            'email' => $this->string(),
            'provider' => $this->string(255)->notNull(),
            'client_id' => $this->string(255)->notNull(),
            'code' => $this->string(32)->unique(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'data' => $this->text(),
        ], $tableOptions);

        $this->createIndex('account_unique', '{{%user_social_account}}', ['provider', 'client_id'], true);
        $this->addForeignKey('{{%user_account_ibfk_1}}', '{{%user_social_account}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_social_account}}');
    }
}
