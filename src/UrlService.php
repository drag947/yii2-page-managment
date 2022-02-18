<?php

namespace drag947\pm;

use Yii;
use drag947\pm\models\PmAlias;
use drag947\pm\models\PmSlugs;
use drag947\pm\models\PageManagment;
use drag947\pm\models\PageParams;
use drag947\pm\models\SeoManagment;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
/**
 * Description of UrlService
 *
 * @author ilya
 */
class UrlService {
    
    public function __construct() {
        ;
    }
    
    
    public function createPage(string $path) {
        
        list($route, $params) = $this->findRealUrlPage($path);
        
        if(!$this->isUniquePage($route, $params)) {
            throw new MessageException('Страница с таким url уже создана');
        }
        
        $page = new PageManagment();
        $page->route = $route;
        $seo = new SeoManagment();
        $seo->lang = Yii::$app->language;
        
        
            
        if( !$page->save() ) {
            throw new PmException('page not save. path:'.$path);
        }
        $seo->page_id = $page->id;
        if( !$seo->save() ) {
            throw new PmException('seo for page not save. path:'.$path);
        }

        foreach ($params as $key => $value) {
            $p = new PageParams();
            $p->page_id = $page->id;
            $p->param = $key;
            $p->value = $value;
            if( !$p->save() ) {
                throw new PmException('param for page not save param:'.$key.' value:'.$value);
            }
        }    
    }
    
    public function updatePage() {
        
    }
    
    public function deletePage() {
        
    }
    
    private function findRealUrlPage($path) {
        $alias = PmAlias::find()->where(['route' => $path])->limit(1)->one();
        if($alias) {
            $result = [$alias->page->route, $alias->page->params];
        }else{
            list($route, $params) = self::spreadUrl($path);
            $request = new \yii\web\Request();
            $request->setUrl($path);
            $request->setQueryParams($params);
            $result = Yii::$app->urlManagerFrontend->parseRequest($request);
            $result[1] = array_merge($result[1], $params);
        }
        
        return $result;
    }
    
    private function isUniquePage($route, $params) {
        $routes = PageManagment::find()->with('params')->where(['route' => $route])->all();
        
        if(!$routes) {
            return true;
        }
        
        foreach ($routes as $route) {
            if(!$route->params) {
                continue;
            }
            $pageParams = ArrayHelper::map($route->params, 'param', 'value');
            if( $pageParams == $params ) {
                return false;
            }
        }
        
        return false;
    }
    
    public static function replace($alia, $path) {
        list($route, $params) = $this->spreadUrl($path);
        $params = $this->replaceSlug($params);
        
        foreach ($params as $key => $param) {
            $alia = str_replace('<'.$key.'>', $param, $alia);
        }
        return $alia;
    }
    
    private function replaceSlug($params) {
        $result = [];
        foreach ($params as $key => $value) {
            $slug = PmSlugs::findOne(['key' => $value, 'param' => $key]);
            
            if(!$slug) {
                $result[$key] = $value;
                continue;
            }
            $result[$key] = $slug->value;
        }
        return $result;
    }
    
    public static function builtUrl($route, $params = []) {
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
    
    public static function spreadUrl($url) {
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
}

class Test {
    
    public function getPathInfo() {
        
    }
}