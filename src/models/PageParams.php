<?php

namespace drag947\pm\models;

/**
 * Description of PageParams
 *
 * @author ilya
 */
class PageParams extends \yii\db\ActiveRecord {
    
    
    public static function tableName() {
        return '{{%page_params}}';
    }
    
    public function rules() {
        return [
            [['page_id', 'param'], 'required'],
            ['param', 'string', 'max' => 255],
            ['page_id', 'integer']
        ];
    }
}
