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
    
    
    public static function tableName() {
        return '{{%pm_meta_keys}}';
    }
    
    public function rules() {
        return [
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['h_one', 'title', 'description', 'image'], 'string', 'max'=>255],
            [['keywords', 'text'], 'string'],
            [['lang'], 'string', 'max'=>10],
            ['image', 'url', 'defaultScheme' => 'https'],
            [['id', 'lang'], 'unique', 'targetAttribute' => 'id'],
            ['is_main', 'boolean'],
            ['is_main', 'default', 'value' => 0]
        ];
    }
    
    public function attributeLabels() {
        return [
            'page_id' => Yii::t('pm', 'Page ID'),
            'h_one' => Yii::t('pm', 'h1'),
            'title' => Yii::t('pm', 'Title'),
            'description' => Yii::t('pm', 'Description'),
            'image' => Yii::t('pm', 'Image'),
            'keywords' => Yii::t('pm', 'Keywords'),
            'text' => Yii::t('pm', 'SEO Text'),
            'lang' => Yii::t('pm', 'Lang'),
            'is_main' => Yii::t('pm', 'Default value')
        ];
    }
    
    public function getPage() {
        return $this->hasOne(PageManagment::class, ['id'=>'page_id']);
    }
}
