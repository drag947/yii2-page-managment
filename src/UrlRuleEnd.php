<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm;

use Yii;
use yii\base\BaseObject;
use yii\web\UrlRuleInterface;
/**
 * Description of UrlRule
 *
 * @author ilya
 */
class UrlRuleEnd extends BaseObject implements UrlRuleInterface {
    
    public function createUrl($manager, $route, $params) {
        if(isset(Yii::$app->params['urlRule'])) {
            $rule = Yii::$app->params['urlRule'];
            if( $route === $rule['route'] && (!$params || array_intersect_assoc($params, $rule['defaults'])) ) {
                unset(Yii::$app->params['urlRule']);
                return $rule['pattern'];
            }
            unset(Yii::$app->params['urlRule']);
        }
        return false;
    }

    public function parseRequest($manager, $request) {
        if( isset(Yii::$app->params['urlR']) ) {
            $pathInfo = $request->getPathInfo();
            if (preg_match('%^(\w+)(/(\w+))+?$%', $pathInfo, $matches)) {                
                if( $matches[0] === Yii::$app->params['urlR']['route'] ) {
                    $route = Yii::$app->params['urlR'];
                    unset(Yii::$app->params['urlRule']);
                    return [$route['route'], $route['defaults']];
                }
            }
            unset(Yii::$app->params['urlR']);
        }
        return false;
        
    }
}