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
use yii\helpers\Url;
use yii\base\Widget;

/**
 * Description of MetaKeys
 *
 * @author ilya
 */
class MetaKeys extends Widget {
    
    
    public function run() {
        $object = new MetaKeys();
        $page = $object->getPage(Yii::$app->request->getPathInfo());
        $lang = Yii::$app->language;
        if($page) {
            $keys = $object->getMetaKeys($page->id, $lang);
            if($keys) {
                return $object->getOpenGraph($keys, $lang);
            }
        }
    }
    
    private function getMetaKeys($page_id, $lang) {
        $keys = SeoManagment::findOne(['page_id' => $page_id, 'lang' => $lang]);
        return $keys;
    }
    
    private function getPage($url) {
        $page = PageManagment::findOne(['path'=>$url]);
        return $page;
    }
    
    private function getOpenGraph($keys, $lang) {
        $meta = '';
        $meta .= '<meta property = "og:locale" content = "'.$lang.'" />';
        if($keys->title) {
            $meta .= '<title>'.$keys->title.'</title>';
            $meta .= '<meta property="og:title" content="'.$keys->title.'" />';
        }
        if($keys->image) {
            $meta .= '<meta property = "og:image" content = "'.$keys->image.'" />';
        }
        if($keys->description) {
            $meta .= '<meta property = "og:description" content = "'.$keys->description.'" />';
        }
        if($keys->keywords) {
            $meta .= '<meta name = "keywords" content = "'.$keys->keywords.'" />';
        }
        return $meta;
    }
}
