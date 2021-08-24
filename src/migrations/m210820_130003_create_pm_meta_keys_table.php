<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pm_meta_keys}}`.
 */
class m210820_130003_create_pm_meta_keys_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pm_meta_keys}}', [
            'id'          => $this->primaryKey(),
            'page_id'     => $this->integer(11)->unsigned()->notNull(),
            'h_one'       => $this->string(255)->Null(),
            'title'       => $this->string(255)->null(),
            'description' => $this->string(255)->null(),
            'keywords'    => $this->text()->null(),
            'text'        => $this->text()->null(),
            'lang'        => $this->string(100)->notNull()
        ]);
        
        $this->createIndex(
            'idx-pm_meta_keys-page_id',
            '{{%pm_meta_keys}}',
            'page_id'
        );

        
        $this->addForeignKey(
            'fk-pm_meta_keys-page_id',
            '{{%pm_meta_keys}}',
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
        $this->dropTable('{{%pm_meta_keys}}');
    }
}
