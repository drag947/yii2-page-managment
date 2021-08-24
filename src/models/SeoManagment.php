<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\models;

use Yii;
use yii\db\ActiveRecord;
use drag947\pm\models\PageManagment;
/**
 * Description of SeoManagment
 *
 * @author ilya
 */
class SeoManagment extends ActiveRecord {
    
    public $image = false;
    
    public static function tableName() {
        return '{{%pm_meta_keys}}';
    }
    
    public function rules() {
        return [
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['h_one', 'title', 'description'], 'string', 'max'=>255],
            [['keywords', 'text'], 'string'],
            [['lang'], 'string', 'max'=>10]
        ];
    }
    
    public function getPage() {
        return $this->hasOne(PageManagment::class, ['id'=>'page_id']);
    }
}
