<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\models;

use Yii;
use yii\db\ActiveRecord;
use drag947\pm\models\SeoManagment;
use drag947\pm\UrlService;
use yii\helpers\ArrayHelper;
/**
 * Description of PageManagment
 *
 * @author ilya
 */
class PageManagment extends ActiveRecord {
    
    private $full_url;
    
    public static function tableName() {
        return "{{%page_managment}}";
    }
    
    public function rules() {
        return [
            [['route'], 'required'],
            [['route'], 'string', 'max'=>255]
        ];
    }
    
    public static function findByRouteAndParams($route, $params) {
        $routes = PageManagment::find()->with('params')->where(['route' => $route])->all();
        
        if(!$routes) {
            return null;
        }
        
        foreach ($routes as $route) {
            if(!$route->params) {
                continue;
            }
            $pageParams = ArrayHelper::map($route->params, 'param', 'value');
            if( $pageParams == $params ) {
                return $route;
            }
        }
        if(!$params) {
            return current($routes);
        }
        
        return null;
    }
    
    public function getAlias() {
        return $this->hasMany(PmAlias::class, ['page_id' => 'id']);
    }
    
    public function getParams() {
        return $this->hasMany(PageParams::class, ['page_id' => 'id']);
    }
    
    public function getPath() {
        if(!$this->full_url) {
            $this->full_url = UrlService::builtUrl($this->route, ArrayHelper::map($this->params, 'param', 'value') );
        }
        return $this->full_url;
    }
    
}
