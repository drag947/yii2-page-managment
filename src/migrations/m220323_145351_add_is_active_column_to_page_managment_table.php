<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%page_managment}}`.
 */
class m220323_145351_add_is_active_column_to_page_managment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%page_managment}}', 'is_active', $this->boolean()->notNull()->defaultValue(1));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%page_managment}}', 'is_active');
    }
}
