<?php

use yii\db\Migration;
use yii\db\Schema;

class m160614_080535_create_history_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('emails_history', [
            'email_id' => $this->integer()->notNull(),
            'action' => Schema::TYPE_STRING . '(20) NOT NULL',
            'created_at' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex('email_id', '{{%emails_history}}', 'email_id');
        $this->addForeignKey('FK_EMAILS_EMAILS_HISTORY_EMAIL_ID', '{{%emails_history}}', 'email_id', '{{%emails}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('emails_history');
    }
}
