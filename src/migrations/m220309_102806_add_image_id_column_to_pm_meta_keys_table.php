<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%pm_meta_keys}}`.
 */
class m220309_102806_add_image_id_column_to_pm_meta_keys_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%pm_meta_keys}}', 'image', $this->string(255)->null());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%pm_meta_keys}}', 'image');
    }
}
