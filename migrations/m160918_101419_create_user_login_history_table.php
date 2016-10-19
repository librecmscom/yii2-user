<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_login_history`.
 */
class m160918_101419_create_user_login_history_table extends Migration
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
         * 登陆历史表
         */
        $this->createTable('{{%user_login_history}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer()->notNull(),
            'ip' => $this->string()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_login_history_ibfk_1}}', '{{%user_login_history}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_login_history}}');
    }
}
