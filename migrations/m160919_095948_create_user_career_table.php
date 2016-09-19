<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_career`.
 */
class m160919_095948_create_user_career_table extends Migration
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
         * 工作经历表
         */
        $this->createTable('{{%user_career}}', [
            'id' => $this->primaryKey(11),
            'user_id' => $this->integer(),
            'name' => $this->string()->notNull(),
            'position' => $this->string()->notNull(),
            'city' => $this->string()->notNull(),
            'description' => $this->string()->notNull(),
            'start_at' => $this->string(7)->notNull(),
            'end_at' => $this->string(7),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
        $this->addForeignKey('{{%user_career_history_ibfk_1}}', '{{%user_career}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_career}}');
    }
}
