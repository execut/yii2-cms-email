<?php

use yii\db\Schema;
use yii\db\Migration;

class m141117_090705_add_default_permissions extends Migration
{
    public function safeUp()
    {
        // Create the auth items
        $this->insert('{{%auth_item}}', [
            'name'          => 'showEmailModule',
            'type'          => 2,
            'description'   => 'Show email module in main-menu',
            'created_at'    => time(),
            'updated_at'    => time()
        ]);
        
        // Create the auth item relation
        $this->insert('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showEmailModule'
        ]);
    }

    public function safeDown()
    {
        // Delete the auth item relation       
        $this->delete('{{%auth_item_child}}', [
            'parent'        => 'Superadmin',
            'child'         => 'showEmailModule'
        ]);

        // Delete the auth items
        $this->delete('{{%auth_item}}', [
            'name'          => 'showEmailModule',
            'type'          => 2,
        ]);
    }
}
