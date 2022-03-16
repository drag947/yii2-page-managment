<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%page_managment}}`.
 */
class m220315_084646_add_is_group_column_to_page_managment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%page_managment}}', 'is_group', $this->boolean()->defaultValue(0));
        $this->addColumn('{{%page_managment}}', 'group_id', $this->integer(11)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%page_managment}}', 'is_group');
        $this->dropColumn('{{%page_managment}}', 'group_id');
    }
}
