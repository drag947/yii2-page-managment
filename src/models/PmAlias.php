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
            [['url', 'route'], 'string', 'max'=>255],
            [['url'], 'unique'],
            [['url', 'route'], 'filter', 'filter'=>function($value) {
                return ($value === '/') ? $value : trim($value, '/');
            }]
        ];
    }
    
    public function getPage() {
        return $this->hasOne(PageManagment::class, ['id'=>'page_id']);
    }
    
    public function getFullUrl() {
        list($route, $params) = $this->spreadUrl($this->page->path);
        $params = $this->replaceSlug($params);
        foreach ($params as $key => $value) {
            $this->url = str_replace('<'.$key.'>', $value, $this->url);
        }
        return $this->url;
    }
    
    private function replaceSlug($params) {
        $result = [];
        foreach ($params as $key => $value) {
            $slug = PmSlugs::findOne(['value' => $value, 'param' => $key]);
            if(!$slug) {
                $result[$key] = $value;
                continue;
            }
            $result[$key] = $slug->key;
        }
        return $result;
    }
    
    private function spreadUrl($url) {
        $params = [];
        $result = explode('?', $url);
        $route = $result[0];
        
        if(isset($result[1])) {
            foreach (explode('&', $result[1]) as $key => $value) {
                $i = strpos($value, '=');
                $params[substr($value, 0, $i)] = substr($value, $i + 1); 
            }
        }
       
        return [$route, $params];
    }
    
    private function builtUrl($route, $params = []) {
        $url = $route;
        if(empty($params)) {
            return $url;
        }
        $url .= '?';
        foreach ($params as $key => $value) {
            $url .= $key.'='.$value.'&';
        }
        $url = trim($url, '&');
        return $url;
    }
}
