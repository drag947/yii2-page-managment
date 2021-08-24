<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_managment}}`.
 */
class m210820_125031_create_page_managment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page_managment}}', [
            'id' => $this->primaryKey()->unsigned(),
            'path' => $this->string(255)->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page_managment}}');
    }
}
