<?php


namespace drag947\pm;

use Yii;
use drag947\pm\models\PmAlias;
use drag947\pm\models\PmSlugs;
use drag947\pm\models\PageManagment;

/**
 * Description of Request
 *
 * @author ilya
 */
class Request extends \yii\web\Request {
    
    public $page_id;
    public $page;
    
    public function resolve(): array {
        
        $url = $this->getPathInfo();
        $result = $this->runAlias($url);
        if(!$result) {
            $result = Yii::$app->getUrlManager()->parseRequest($this);
        }
        $baseUrl = $this->builtUrl($url, $this->getQueryParams());
        if ($result !== false) {
            list($route, $params) = $result;
            
            $params = $this->replaceSlug($params);
            if ($this->getQueryParams() === null) {
                $_GET = $params + $_GET; // preserve numeric keys
            } else {
                $this->setQueryParams($params + $this->getQueryParams());
            }
           
            $lastUrl = Yii::$app->getUrlManager()->createUrl(array_merge([0=>$route], $this->replaceSlugReverse($this->getQueryParams()) ));
            //var_dump($lastUrl, $baseUrl);die();
            //$result = $this->runAlias($this->builtUrl($route, $this->getQueryParams()));
            if($lastUrl !== '/'.$baseUrl) {
                //Yii::$app->response->redirect([$lastUrl], 301)->send();
                //die();
            }
            $page = PageManagment::findByRouteAndParams($route, $this->getQueryParams());
            if($page) {
                $this->page_id = $page->id;
            }
            
            return [$route, $this->getQueryParams()];
        }

        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
    }
    
    private function runAlias($url) {
        $result = false;
        $page = PmAlias::find()->where(['route' => $url])->limit(1)->one();
        //var_dump($page);
        if($page) {
            $this->page_id = $page->id;
            $alias = PmAlias::find()->with('page')->where(['page_id'=>$page->page_id])->orderBy('sort asc')->limit(1)->one();
            
            if($alias && $alias->route != $url) {
                Yii::$app->response->redirect([$alias->route], 301)->send();
                die();
            }
            $result = $this->spreadUrl($alias->page->path);
        }
        return $result;
    }
    
    private function replaceSlug($params) {
        $result = [];
        foreach ($params as $key => $value) {
            $slug = PmSlugs::findOne(['value' => $value, 'param' => $key]);
            if(!$slug) {
                $result[$key] = $value;
                continue;
            }
            $result[$key] = $slug->key;
        }
        return $result;
    }
    private function replaceSlugReverse($params) {
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
    
    private function spreadUrl($url) {
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
