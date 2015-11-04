<?php

use yii\db\Schema;
use yii\db\Migration;
use infoweb\email\models\Email;

class m151104_134304_add_action_column extends Migration
{
    public function up()
    {
        $this->addColumn('{{%emails}}', 'action', Schema::TYPE_STRING."(25) NOT NULL DEFAULT '".Email::ACTION_RECEIVED."'");
    }

    public function down()
    {
        $this->dropColumn('{{%emails}}', 'action');
    }
}
