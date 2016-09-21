<?php

use yii\db\Migration;

/**
 * Handles the creation for table `notifcation`.
 */
class m160921_093501_create_notifcation_table extends Migration
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

        $this->createTable('{{%user_notifcation}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'to_user_id' => $this->integer(),
            'type' => $this->string(),
            'subject' => $this->string(),
            'source_id' => $this->integer(),
            'refer_content' => $this->string(),
            'status' => $this->integer(2),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_notifcation}}');
    }
}
