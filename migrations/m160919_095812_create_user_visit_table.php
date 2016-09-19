<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_visit`.
 */
class m160919_095812_create_user_visit_table extends Migration
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
         * 用户访问历史表
         */
        $this->createTable('{{%user_visit}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer()->notNull(),
            'visit_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_visit_ibfk_1}}', '{{%user_visit}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%user_visit_ibfk_2}}', '{{%user_visit}}', 'visit_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_visit}}');
    }
}
