<?php

use yii\db\Schema;
use yii\db\Migration;

class m141117_075617_init extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Create 'emails' table
        $this->createTable('{{%emails}}', [
            'id'            => $this->primaryKey(),
            'language'      => $this->string(10)->notNull(),
            'form'          => $this->string()->notNull(),
            'from'          => $this->string()->notNull(),
            'to'            => $this->string()->notNull(),
            'subject'       => $this->string()->notNull(),
            'message'       => Schema::TYPE_TEXT . ' NOT NULL',
            'read'          => $this->integer()->notNull()->defaultValue('0'),
            'created_at'    => $this->integer()->unsigned()->notNull(),
            'updated_at'    => $this->integer()->unsigned()->notNull(),
            'read_at'       => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('emails');
    }
}
