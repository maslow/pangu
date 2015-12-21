<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_130657_create_table_manager extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%manager}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(32)->unique()->notNull(),
            'password_hash' => $this->string(64)->notNull(),
            'auth_key' => $this->string(64)->notNull(),
            'locked' => $this->boolean()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'created_ip' => $this->string(16)->notNull(),
            'created_by' => $this->integer()->notNull()
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%manager}}');
        return true;
    }
}
