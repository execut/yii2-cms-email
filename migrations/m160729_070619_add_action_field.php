<?php

use yii\db\Migration;
use yii\db\Schema;

use infoweb\email\models\Template;

class m160729_070619_add_action_field extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('{{%emails_templates}}', 'action', Schema::TYPE_STRING."(25) NOT NULL DEFAULT '".Template::ACTION_RECEIVED."'");
    }

    public function safeDown()
    {
        $this->dropColumn('{{%emails_templates}}', 'action');
    }
}