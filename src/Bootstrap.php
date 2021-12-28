<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm;

use Yii;
use yii\base\BootstrapInterface;
use drag947\pm\models\PmAlias;
use yii\web\UrlNormalizer;
use yii\helpers\Url;
use drag947\pm\UrlRule;
/**
 * Description of Bootstrap
 *
 * @author ilya
 */
class Bootstrap implements BootstrapInterface {
    
    public function bootstrap($app) {
        $this->addRules($app);
        //$app->getSession()->removeAll();
        $this->redirect($app);
        
    }
    
    private function redirect1($app) {
        $alias = PmAlias::find()->where(['url'=>$app->request->getPathInfo()])->limit(1)->one();
        if($alias) {
            $redirectUrl = PmAlias::find()->where(['page_id'=>$alias->page_id])->orderBy('id desc')->limit(1)->one();
            
            if($redirectUrl && $redirectUrl->id != $alias->id) {
                $app->response->redirect([$redirectUrl->url], 301)->send();
                die();
            }
            
        }
    }
    
    private function redirect($app) {
        Yii::$app->session->open();
        $urlManager = $app->getUrlManager();
        if($urlManager) {
            
            $redirectUrl = $app->request->resolve();
            $redirectUrl = $this->builtUrl($redirectUrl[0], $redirectUrl[1]);
            $redirect = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')
                        ->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')
                        ->where(['{{%page_managment}}.path'=>$redirectUrl])
                        ->orderBy('{{%pm_alias}}.id desc')->limit(1)->one();//var_dump(trim($_SERVER['REQUEST_URI'],'/'));var_dump($redirectUrl);die();
            if( $redirect && $redirect->url !== trim($_SERVER['REQUEST_URI'],'/') ) {
                Url::remember($app->request->getPathInfo(), 'url-'.$app->request->getPathInfo());
                
                if($redirect && !isset($_SESSION['url-'.$redirect->url])) {
                    $this->removeSession('url-'.$app->request->getPathInfo());
                    $app->response->redirect([$redirect->url], 301)->send();
                    die();
                }
                
            }
            $this->removeSession();
        }
    }
    
    private function removeSession($except = null) {
        foreach ($_SESSION as $key => $session) {
            if(strpos($key, 'url-') === 0) {
                if($except != $key)
                    unset($_SESSION[$key]);
            }
        }
    }
    
    private function addRules($app) {
        $rules = $this->newRules($app);
        if($rules) {
            $app->getUrlManager()->addRules($rules, false);
        }
        //$app->getUrlManager()->addRules($this->endRule(), true);
    }
    
    private function newRules($app) {
        /*$alias = PmAlias::findBySql("SELECT alias.id, alias.page_id, alias.url, pm.path FROM {{%pm_alias}} as alias"
                . " LEFT JOIN (SELECT Max(id) as max_id, page_id FROM {{%pm_alias}} GROUP BY `page_id`) as max ON max.page_id = alias.page_id"
                . " LEFT JOIN {{%page_managment}} as pm ON pm.id = alias.page_id"
                . " WHERE alias.id = max.max_id;")->asArray()->all();*/
        $alias = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')->orderBy('id desc')->asArray()->all();
        //$alias = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')->where(['{{%pm_alias}}.url'=>$app->request->getPathInfo()])->limit(1)->asArray()->one();
        $result = false;        
        if($alias) {
            foreach ($alias as $alia) {
                $defaults = [];
                $path = $alia['path'];
                if($pos = strpos($alia['path'], '?')) {
                    $str = explode('?', $alia['path']);
                    $path = $str[0];
                    $params = explode('&', $str[1]);
                    foreach ($params as $k => $v) {
                        $explode = explode('=', $v);
                        $defaults[$explode[0]] = $explode[1];
                    }
                }
                $result[] = [
                    //'class' => UrlRule::class,
                    'pattern'=>$alia['url'],
                    'route' => $path,
                    'defaults' => $defaults
                    ];
            }
        }
        return $result;
    }
    
    private function endRule() {
        return [
            ['class' => UrlRuleEnd::class]
        ];
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
