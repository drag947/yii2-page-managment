<?php


namespace drag947\pm;

use Yii;
use drag947\pm\models\PmAlias;
use drag947\pm\models\PmSlugs;

/**
 * Description of Request
 *
 * @author ilya
 */
class Request extends \yii\web\Request {
    
    
    public function resolve(): array {
        
        $url = $this->getPathInfo();
        
        $page = false;
        $page = PmAlias::find()->where(['route' => $url])->limit(1)->one();
        if($page) {
            $alias = PmAlias::find()->with('page')->where(['page_id'=>$page->page_id])->orderBy('sort asc')->limit(1)->one();
            
            if($alias && $alias->route != $url) {
                Yii::$app->response->redirect([$alias->route], 301)->send();
                die();
            }
            $result = $this->spreadUrl($alias->page->path);
        }else{
            $result = Yii::$app->getUrlManager()->parseRequest($this);
        }
        
        if ($result !== false) {
            list($route, $params) = $result;
            $params = $this->replaceSlug($params);
            if ($this->getQueryParams() === null) {
                $_GET = $params + $_GET; // preserve numeric keys
            } else {
                $this->setQueryParams($params + $this->getQueryParams());
            }

            return [$route, $this->getQueryParams()];
        }

        throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
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
