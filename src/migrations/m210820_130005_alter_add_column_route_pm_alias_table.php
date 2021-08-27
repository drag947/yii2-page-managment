<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pm_meta_keys}}`.
 */
class m210820_130005_alter_add_column_route_pm_alias_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pm_alias}}', 'route', $this->string(255));
        
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pm_alias}}', 'route');
    }
}
