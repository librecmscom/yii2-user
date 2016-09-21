<?php

use yii\db\Migration;

/**
 * Handles the creation for table `doing`.
 */
class m160921_092803_create_doing_table extends Migration
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
        $this->createTable('{{%user_doing}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'action' => $this->string(),
            'source_id' => $this->integer(),
            'source_type' => $this->string(),
            'subject' => $this->string(),
            'content' => $this->string(),
            'refer_id' => $this->integer(),
            'refer_user_id' => $this->integer(),
            'refer_content' => $this->string(),
            'created_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey('{{%user_doing_history_ibfk_1}}', '{{%user_doing_history}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_doing}}');
    }
}
