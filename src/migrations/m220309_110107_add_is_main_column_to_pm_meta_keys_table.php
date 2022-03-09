<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pm_meta_keys}}`.
 */
class m220309_110107_add_is_main_column_to_pm_meta_keys_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pm_meta_keys}}', 'is_main', $this->boolean()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pm_meta_keys}}', 'is_main');
    }
}
