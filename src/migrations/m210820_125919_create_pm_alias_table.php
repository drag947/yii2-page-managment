<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pm_alias}}`.
 */
class m210820_125919_create_pm_alias_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('IF NOT EXISTS {{%pm_alias}}', [
            'id' => $this->primaryKey()->unsigned(),
            'page_id' => $this->integer()->unsigned()->notNull(),
            'url' => $this->string(255)->notNull()
        ]);
        
        $this->createIndex(
            'idx-pm_alias-page_id',
            '{{%pm_alias}}',
            'page_id'
        );

        
        $this->addForeignKey(
            'fk-pm_alias-page_id',
            '{{%pm_alias}}',
            'page_id',
            '{{%page_managment}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pm_alias}}');
    }
}
