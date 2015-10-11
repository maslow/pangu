<?php

use yii\db\Schema;
use yii\db\Migration;

class m151011_053126_create_table extends Migration
{
    private $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

    public function up()
    {
        $this->createTable('{{%user}}',[
            'id'=>$this->primaryKey(),
            'username'=>$this->string(32)->unique()->notNull(),
            'password_hash'=>$this->string(64)->notNull(),
            'auth_key'=>$this->string(64)->notNull(),
            'created_at'=>$this->integer()->notNull(),
            'updated_at'=>$this->integer()->notNull()
        ],$this->tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        return true;
    }

}
