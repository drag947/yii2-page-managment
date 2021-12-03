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
class UrlRule extends BaseObject implements UrlRuleInterface {
    
    public $pattern;
    public $route;
    public $defaults = [];
    
    public function createUrl($manager, $route, $params) {
        if($route === $this->route && (!$params || array_intersect_assoc($params, $this->defaults))) {
            Yii::$app->params['urlRule']['pattern'] = $this->pattern;
            Yii::$app->params['urlRule']['route'] = $this->route;
            Yii::$app->params['urlRule']['defaults'] = $this->defaults;
        }
        return false;
    }

    public function parseRequest($manager, $request) {
        
        $pathInfo = $request->getPathInfo();
        if (preg_match('%^(\w+)(/(\w+))?$%', $pathInfo, $matches)) {
            if( $matches[0] === $this->pattern ) {
                $request->setUrl($this->builtUrl($this->route, $this->defaults));
                $request->setPathInfo('/'.$this->route);
                Yii::$app->params['urlR']['pattern'] = $this->pattern;
                Yii::$app->params['urlR']['route'] = $this->route;
                Yii::$app->params['urlR']['defaults'] = $this->defaults;
            }
        }
        return false;
        
    }
    
    private function builtUrl($route, $params = []) {
        $url = '/'.$route;
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
