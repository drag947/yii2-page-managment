<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pm_slugs}}`.
 */
class m220215_113206_create_pm_slugs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pm_slugs}}', [
            'id' => $this->primaryKey(),
            'param' => $this->string()->notNull(),
            'key' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pm_slugs}}');
    }
}
