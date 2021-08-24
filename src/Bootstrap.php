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
/**
 * Description of Bootstrap
 *
 * @author ilya
 */
class Bootstrap implements BootstrapInterface {
    
    public function bootstrap($app) {
        $this->redirect($app);
    }
    
    private function redirect($app) {
        $alias = PmAlias::find()->where(['url'=>$app->request->getPathInfo()])->limit(1)->one();
        if($alias) {
            $app->response->redirect([$alias->page->path], 302)->send();
            die();
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
        $result = false;
        foreach ($alias as $alia) {
            $result[] = [
                'pattern'=>$alia['url'],
                'route' => $alia['path'],
                ];
        }
        return $result;
    }
    
    
}
