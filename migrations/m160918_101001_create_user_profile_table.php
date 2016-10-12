<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_profile`.
 */
class m160918_101001_create_user_profile_table extends Migration
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
         * 创建用户资料表
         */
        $this->createTable('{{%user_profile}}', [
            'user_id' => $this->integer()->notNull() . ' PRIMARY KEY',
            'nickname' => $this->string(),
            'sex' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'mobile' => $this->string(),
            'public_email' => $this->string(),
            'location' => $this->string(),
            'address' => $this->string(),
            'website' => $this->string(),
            'avatar' => $this->boolean()->defaultValue(false),
            'timezone' => $this->string(),
            'introduction' => $this->string(),
            'bio' => $this->text()
        ], $tableOptions);
        $this->addForeignKey('{{%user_profile_ibfk_1}}', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_profile}}');
    }
}
