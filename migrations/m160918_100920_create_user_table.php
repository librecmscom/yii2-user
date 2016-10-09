<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160918_100920_create_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(11),
            'username' => $this->string()->notNull()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string()->notNull(),
            'password_hash' => $this->string()->notNull(),
            'amount' => $this->decimal(10, 2)->defaultValue('0.00'),
            'point' => $this->integer()->defaultValue(0),
            'confirmed_at' => $this->integer(),
            'unconfirmed_email' => $this->string(255),
            'blocked_at' => $this->integer()->unsigned(),
            'login_num' => $this->integer()->unsigned()->defaultValue(0),
            'registration_ip' => $this->string(),
            'flags' => $this->integer()->notNull()->defaultValue(0),
            'weights' => $this->integer()->defaultValue(0),
            'last_login_at' => $this->integer()->unsigned(),
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
