<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_rest`.
 */
class m161013_022124_create_user_rest_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB AUTO_INCREMENT=10000';
        }

        $this->createTable('{{%user_rest}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'type' => $this->string(50)->notNull(),
            'token' => $this->string()->notNull(),
            'auth_key' => $this->string()->notNull(),
            'rate_limit' => $this->integer(8)->defaultValue(10),
            'rate_period' => $this->integer(8)->defaultValue(31536000),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%user_rest_token_unique}}', '{{%user_rest}}', ['id', 'token'], true);
        $this->addForeignKey('{{%user_rest_ibfk_1}}', '{{%user_rest}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_rest}}');
    }
}
