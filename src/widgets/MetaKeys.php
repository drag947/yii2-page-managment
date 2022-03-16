<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\widgets;

use Yii;
use drag947\pm\models\SeoManagment;
use drag947\pm\models\PageManagment;
use drag947\pm\models\PmAlias;
use yii\helpers\Url;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

/**
 * Description of MetaKeys
 *
 * @author ilya
 */
class MetaKeys extends Widget {
    
    private static $keys = false;
    public $params = [];
    
    public function init() {
        parent::init();
        
    }
    
    public function run() {
        $page_id = Yii::$app->request->page_id;
        $lang = Yii::$app->language;
        if($page_id) {
            $keys = self::getMetaKeys($page_id, $lang, Yii::$app->request->page->group_id, $this->params);
            if($keys) {
                return $this->getOpenGraph($keys, $lang);
            }
        }
        return '';
    }
    
    public static function getHOne() {
        $page_id = Yii::$app->request->page_id;
        $lang = Yii::$app->language;
        $group_id = Yii::$app->request->page ? Yii::$app->request->page->group_id : false;
        $params = isset(Yii::$app->view->params['seo']) ? Yii::$app->view->params['seo'] : [];
        $keys = self::getMetaKeys($page_id, $lang, $group_id, $params);
        if($keys && $keys['h_one']) {
            return Html::encode($keys['h_one']);
        }
        return '';
    }
    
    public static function getSeoText() {
        $page_id = Yii::$app->request->page_id;
        $lang = Yii::$app->language;
        $params = isset(Yii::$app->view->params['seo']) ? Yii::$app->view->params['seo'] : [];
        $group_id = Yii::$app->request->page ? Yii::$app->request->page->group_id : false;
        $keys = self::getMetaKeys($page_id, $lang, $group_id, $params);
        if($keys && $keys['text']) {
            return HtmlPurifier::process($keys['text']);
        }
        return '';
    }
    
    private static function isEmpty($keys) {
        if(!$keys) {
            return true;
        }
        $keys = array_values($keys->getAttributes(['title', 'description', 'image', 'keywords', 'h_one']));
        foreach ($keys as $key) {
            if($key) {
                return false;
            }
        }
        return true;
    }
    
    private static function getMetaKeys($page_id, $lang, $group_id, $params = []) {
        $seo = [];
        if(self::$keys === false) {
            $seo = SeoManagment::findOne(['page_id' => $page_id, 'lang' => $lang]);
            
            if(self::isEmpty($seo) && $group_id) {
                $seo = SeoManagment::findOne(['page_id' => $group_id, 'lang' => $lang]);
            }
            if(self::isEmpty($seo)) {
                $seo = SeoManagment::findOne(['is_main' => 1]);
            }
            
            if($seo && $params) {
                $search = [];
                foreach ($params as $key => $param) {
                   $search['<'.$key.'>'] = $param; 
                }
                
                if($search) {
                    $keys = array_keys($search);
                    $seo->title = str_replace($keys, $search, $seo->title);
                    $seo->description = str_replace($keys, $search, $seo->description);
                    $seo->keywords = str_replace($keys, $search, $seo->keywords);
                    $seo->h_one = str_replace($keys, $search, $seo->h_one);
                }
            }
            //$group = SeoManagment::findOne(['page_id' => $group_id, 'lang' => $lang]);
            //$default = SeoManagment::findOne(['is_main' => 1]);
            /*if($seo && $group) {
                foreach ($seo as $key => $value) {
                    if(!$value) {
                        $seo[$key] = $group[$key];
                    }
                }
            }elseif($seo && $default) {
                foreach ($seo as $key => $value) {
                    if(!$value) {
                        $seo[$key] = $default[$key];
                    }
                }
            }*/
            
            self::$keys = $seo;
        }
        return self::$keys;
    }
    /* переделать под url-manager искать по реальному url */
    private function getPageId($url, $params = '') {
        
        $id = false;
        $page = PageManagment::findByRouteAndParams($url, $params);
        var_dump($url, $params);
        if(!$page) {
            $page = PmAlias::findOne(['url' => $url]);
            if($page) {
                $id = $page->page_id;
            }
        }else{
            $id = $page->id;
        }
        
        return $id;
    }
    
    private function getOpenGraph($keys, $lang) {
        $meta = '';
        $meta .= '<meta property = "og:locale" content = "'.$lang.'" />';
        if($keys->title) {
            $meta .= '<title>'.$keys->title.'</title>';
            $meta .= '<meta property="og:title" content="'.Html::encode($keys->title).'" />';
        }
        if($keys->image) {
            $meta .= '<meta property = "og:image" content = "'.$keys->image.'" />';
        }
        if($keys->description) {
            $meta .= '<meta property = "og:description" content = "'.Html::encode($keys->description).'" />';
            $meta .= '<meta name="description" content="'.Html::encode($keys->description).'">';
        }
        if($keys->keywords) {
            $meta .= '<meta name = "keywords" content = "'.Html::encode($keys->keywords).'" />';
        }
        return $meta;
    }
}
