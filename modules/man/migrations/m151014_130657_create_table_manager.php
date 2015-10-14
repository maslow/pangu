<?php

use yii\db\Schema;
use yii\db\Migration;

class m151014_130657_create_table_manager extends Migration
{
    private $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->createTable('{{%manager}}',[
            'id'=>$this->primaryKey(),
            'username'=>$this->string(32)->unique()->notNull(),
            'password_hash'=>$this->string(64)->notNull(),
            'auth_key'=>$this->string(64)->notNull(),
            'locked'=>$this->boolean()->notNull(),
            'updated_at' =>$this->integer()->notNull(),
            'created_at'=>$this->integer()->notNull(),
            'created_ip'=>$this->string(16)->notNull(),
            'created_by'=>$this->integer()->notNull()
        ],$this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%manager}}');
        return true;
    }
}
