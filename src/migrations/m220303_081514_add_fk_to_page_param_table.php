<?php

use yii\db\Migration;

/**
 * Class m220303_081514_add_fk_to_page_param_table
 */
class m220303_081514_add_fk_to_page_param_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk-page_params-page_id', '{{%page_params}}');
        $this->addForeignKey('fk-page_params-page_id', '{{%page_params}}', 'page_id', '{{%page_managment}}', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        
    }
}
