<?php

use yii\db\Migration;
use yii\db\Schema;

class m160728_082312_create_tables_email_templates extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%emails_templates}}', [
            'id' => $this->primaryKey(),
            'type' => "ENUM('system','user-defined') NOT NULL DEFAULT 'user-defined'",
            'name' => Schema::TYPE_STRING . '(255) NOT NULL',
            'supported_tags' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at'  => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
        ], $tableOptions);

        $this->createTable('{{%emails_templates_lang}}', [
            'email_template_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'language' => Schema::TYPE_STRING . '(10) NOT NULL',
            'to' => Schema::TYPE_STRING . '(255) NOT NULL',
            'bcc' => Schema::TYPE_STRING . '(255) NOT NULL',
            'from' => Schema::TYPE_STRING . '(255) NOT NULL',
            'subject' => Schema::TYPE_STRING . '(255) NOT NULL',
            'message' => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL',
            'updated_at' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL'
        ], $tableOptions);

        $this->addPrimaryKey('email_template_id_language', '{{%emails_templates_lang}}', ['email_template_id', 'language']);
        $this->createIndex('language', '{{%emails_templates_lang}}', 'language');
        $this->addForeignKey('FK_EMAILS_TEMPLATES_LANG_EMAILS_TEMPLATES_ID', '{{%emails_templates_lang}}', 'email_template_id', '{{%emails_templates}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%emails_templates}}');
        $this->dropTable('{{%emails_templates_lang}}');
    }
}
