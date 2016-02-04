<?php

use yii\db\Schema;
use yii\db\Migration;

class m160204_134326_add_rep_and_profession extends Migration
{
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('emails', 'rep', Schema::TYPE_STRING . '(255) NOT NULL');
        $this->addColumn('emails', 'profession', Schema::TYPE_STRING . '(255) NOT NULL');
    }

    public function safeDown()
    {
        $this->dropColumn('emails', 'rep');
        $this->dropColumn('emails', 'profession');
    }
}
