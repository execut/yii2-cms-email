<?php

use yii\db\Migration;
use yii\db\Schema;

class m160728_082312_create_tables_email_templates extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%emails_templates}}', [
            'id' => $this->primaryKey(),
            'type' => "pages_type NOT NULL DEFAULT 'user-defined'",
            'name' => $this->string()->notNull(),
            'supported_tags' => $this->text()->notNull(),
            'created_at'  => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%emails_templates_lang}}', [
            'email_template_id' => $this->integer()->notNull(),
            'language' => $this->string(10)->notNull(),
            'to' => $this->string()->notNull(),
            'bcc' => $this->string()->notNull(),
            'from' => $this->string()->notNull(),
            'subject' => $this->string()->notNull(),
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => $this->integer()->unsigned()->notNull(),
            'updated_at' => $this->integer()->unsigned()->notNull()
        ], $tableOptions);

        $this->addPrimaryKey('email_template_id_language', '{{%emails_templates_lang}}', ['email_template_id', 'language']);
        $this->createIndex('emails_templates_lang_language_i', '{{%emails_templates_lang}}', 'language');
        $this->addForeignKey('FK_EMAILS_TEMPLATES_LANG_EMAILS_TEMPLATES_ID', '{{%emails_templates_lang}}', 'email_template_id', '{{%emails_templates}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function safeDown()
    {
        $this->dropTable('{{%emails_templates}}');
        $this->dropTable('{{%emails_templates_lang}}');
    }
}
