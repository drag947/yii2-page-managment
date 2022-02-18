<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pm_alias}}`.
 */
class m220218_123115_add_sort_column_to_pm_alias_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pm_alias}}', 'sort', $this->integer(11)->notNull());
        $this->execute('UPDATE {{%pm_alias}} SET `sort` = `id` WHERE `sort` = 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pm_alias}}', 'sort');
    }
}
