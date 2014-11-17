<?php

use yii\db\Schema;
use yii\db\Migration;

class m141117_075617_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // Create 'emails' table
        $this->createTable('{{%emails}}', [
            'id'            => Schema::TYPE_PK,
            'language'      => Schema::TYPE_STRING . '(2) NOT NULL',
            'form'          => Schema::TYPE_STRING . '(255) NOT NULL',
            'from'          => Schema::TYPE_STRING . '(255) NOT NULL',
            'to'            => Schema::TYPE_STRING . '(255) NOT NULL',
            'subject'       => Schema::TYPE_STRING . '(255) NOT NULL',
            'message'       => Schema::TYPE_TEXT . ' NOT NULL',
            'read'          => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'0\'',
            'created_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at'    => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'read_at'       => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('emails');
    }
}
