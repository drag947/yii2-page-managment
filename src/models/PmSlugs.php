<?php

namespace drag947\pm\models;

/**
 * Description of PmSlugs
 *
 * @author ilya
 */
class PmSlugs extends \yii\db\ActiveRecord {
    
    public static function tableName() {
        return '{{%pm_slugs}}';
    }
    
    public function rules() {
        return [
            [['key', 'value', 'param'], 'required'],
            [['value', 'param'], 'string', 'max' => 255]
        ];
    }
}
