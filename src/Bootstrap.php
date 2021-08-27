<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm;

use yii\base\BootstrapInterface;
use drag947\pm\models\PmAlias;
use yii\web\UrlNormalizer;
use yii\helpers\Url;
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
        $urlManager = $app->getUrlManager();
        if($urlManager) {
            
            $redirectUrl = $urlManager->parseRequest($app->request);
            
            if(isset($redirectUrl[0]) && $redirectUrl[0]) {
                $redirect = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')
                        ->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')
                        ->where(['{{%page_managment}}.path'=>$redirectUrl[0]])
                        ->orderBy('{{%pm_alias}}.id desc')->limit(1)->one();
                Url::remember($app->request->getPathInfo(), 'url-'.$app->request->getPathInfo());
                
                if($redirect && !isset($_SESSION['url-'.$redirect->url])) {
                    $this->removeSession('url-'.$app->request->getPathInfo());
                    $app->response->redirect([$redirect->url], 301)->send();
                    die();
                }
                $this->removeSession();
            }
            
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
    }
    
    private function newRules($app) {
        /*$alias = PmAlias::findBySql("SELECT alias.id, alias.page_id, alias.url, pm.path FROM {{%pm_alias}} as alias"
                . " LEFT JOIN (SELECT Max(id) as max_id, page_id FROM {{%pm_alias}} GROUP BY `page_id`) as max ON max.page_id = alias.page_id"
                . " LEFT JOIN {{%page_managment}} as pm ON pm.id = alias.page_id"
                . " WHERE alias.id = max.max_id;")->asArray()->all();*/
        $alias = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')->asArray()->all();
        //$alias = PmAlias::find()->select('{{%pm_alias}}.*, {{%page_managment}}.path')->leftJoin('{{%page_managment}}', '{{%page_managment}}.id={{%pm_alias}}.page_id')->where(['{{%pm_alias}}.url'=>$app->request->getPathInfo()])->limit(1)->asArray()->one();
        $result = false;        
        if($alias) {
            foreach ($alias as $alia) {
                $result[] = [
                    'pattern'=>$alia['url'],
                    'route' => $alia['path'],
                    ];
            }
        }
        return $result;
    }
    
    
}
