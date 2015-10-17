<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_130657_create_table_manager extends Migration
{
    private $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
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
        ], $this->tableOptions);
        $this->insert('{{%manager}}', [
            'username' => 'ey2b',
            'password_hash' => Yii::$app->security->generatePasswordHash('vip-design.net'),
            'auth_key' => Yii::$app->security->generateRandomString(),
            'locked' => false,
            'updated_at' => time(),
            'created_at' => time(),
            'created_ip' => '127.0.0.1',
            'created_by' => 1
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%manager}}');
        return true;
    }
}
