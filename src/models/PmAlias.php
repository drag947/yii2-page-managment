<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\pm\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * Description of PmAlias
 *
 * @author ilya
 */
class PmAlias extends ActiveRecord {
    
    public static function tableName() {
        return "{{%pm_alias}}";
    }
    
    public function rules() {
        return [
            [['page_id', 'url'], 'required'],
            [['page_id'], 'integer'],
            [['url'], 'string', 'max'=>255]
        ];
    }
}
