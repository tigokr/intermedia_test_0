<?php

use yii\db\Schema;
use yii\db\Migration;

class m150331_114129_emails extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%emails}}', [
            'id'=>Schema::TYPE_TEXT .' (13) UNIQUE PRIMARY KEY',
            'created'=>Schema::TYPE_DATETIME . ' NOT NULL',
            'data'=>Schema::TYPE_TEXT . ' NOT NULL',
            'received'=>Schema::TYPE_BOOLEAN . ' NOT NULL',
        ], $tableOptions);

        return true;
    }

    public function down()
    {
        $this->dropTable('{{%emails}}');

        echo "m150331_114129_emails reverted.\n";

        return true;
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
