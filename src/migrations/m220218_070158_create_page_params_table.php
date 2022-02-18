<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page_params}}`.
 */
class m220218_070158_create_page_params_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page_params}}', [
            'id' => $this->primaryKey(),
            'page_id' => $this->integer()->unsigned()->notNull(),
            'param' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull()
        ]);
        
        $this->createIndex('idx-page_params-page_id', '{{%page_params}}', 'page_id');
        
        $this->addForeignKey('fk-page_params-page_id', '{{%page_params}}', 'page_id', '{{%page_managment}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-page_params-page_id', '{{%page_params}}');
        $this->dropTable('{{%page_params}}');
    }
}
