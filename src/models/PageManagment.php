<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Description of PageManagment
 *
 * @author ilya
 */
class PageManagment extends ActiveRecord {
    
    
    public static function tableName() {
        return "{{%page_managment}}";
    }
    
    public function rules() {
        return [
            [['path'], 'required'],
            [['path'], 'string', 'max'=>255]
        ];
    }
}
