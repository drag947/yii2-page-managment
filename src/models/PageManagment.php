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
            [['route'], 'string', 'max'=>255],
            [['group_id'], 'integer'],
            ['group_id', 'exist', 'targetAttribute' => 'id'],
            [['is_group'], 'boolean'],
            ['is_group', 'default', 'value' => 0]
        ];
    }
    
    public function attributeLabels() {
        return [
            'route' => Yii::t('pm', 'Route')
        ];
    }
    
    public static function findByRouteAndParams($route, $params, $config = []) {
        if(!$route) {
            $route = 'site/index';
        }
        $routes = PageManagment::find()->with('params')->where(['route' => $route, 'is_group' => 0]);
        if(isset($config['is_active'])) {
            $routes->andWhere(['is_active' => $config['is_active']]);
        }
        $routes = $routes->all();
        if(!$routes) {
            return null;
        }
        $routesWithoutParams = [];
        foreach ($routes as $route) {
            if(!$route->params) {
                $routesWithoutParams[] = $route;
                continue;
            }
            $pageParams = ArrayHelper::map($route->params, 'param', 'value');
            if( $pageParams == $params ) {
                return $route;
            }
        }
        if(!$params && $routesWithoutParams) {
            return current($routesWithoutParams);
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
    
    public function getGroup() {
        return $this->hasOne(self::class, ['id' => 'group_id']);
    }
    
}
