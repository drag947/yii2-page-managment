<?php

use yii\db\Migration;

/**
 * Class m220218_073554_alter_page_managment_table
 */
class m220218_073554_alter_page_managment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%page_managment}}', 'path', 'route');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%page_managment}}', 'route', 'path');
    }
}
