<?php


namespace drag947\pm;

use drag947\pm\models\PmSlugs;
use drag947\pm\models\PmAlias;
use drag947\pm\models\PageManagment;

/**
 * Description of UrlManager
 *
 * @author ilya
 */
class UrlManager extends \yii\web\UrlManager {
    
    public function createUrl($params)
    {
        $url = false;
        $params = (array) $params;
        $anchor = isset($params['#']) ? '#' . $params['#'] : '';
        unset($params['#'], $params[$this->routeParam]);

        $route = trim($params[0], '/');
        unset($params[0]);

        $baseUrl = $this->showScriptName || !$this->enablePrettyUrl ? $this->getScriptUrl() : $this->getBaseUrl();

        if ($this->enablePrettyUrl) {
            $cacheKey = $route . '?';
            foreach ($params as $key => $value) {
                if ($value !== null) {
                    $cacheKey .= $key . '&';
                }
            }
            if($url === false) {
                $sendUrl = $this->builtUrl($route, $params);
                $page = PageManagment::findByRouteAndParams($route, $params);
                if($page) {
                    $alia = PmAlias::find()->where(['page_id' => $page->id])->orderBy('sort asc')->limit(1)->one();
                    if($alia) {
                        $url = $this->replace($alia->url, $page->path);
                    }
                }
            }
            if($url === false) {
                $url = $this->getUrlFromCache($cacheKey, $route, $params);
            }
            if ($url === false) {
                /* @var $rule UrlRule */
                foreach ($this->rules as $rule) {
                    //if (in_array($rule, $this->_ruleCache[$cacheKey], true)) {
                        // avoid redundant calls of `UrlRule::createUrl()` for rules checked in `getUrlFromCache()`
                        // @see https://github.com/yiisoft/yii2/issues/14094
                        //continue;
                    //}
                    $url = $rule->createUrl($this, $route, $params);
                    if ($this->canBeCached($rule)) {
                        $this->setRuleToCache($cacheKey, $rule);
                    }
                    if ($url !== false) {
                        break;
                    }
                }
            }

            if ($url !== false) {
                if (strpos($url, '://') !== false) {
                    if ($baseUrl !== '' && ($pos = strpos($url, '/', 8)) !== false) {
                        return substr($url, 0, $pos) . $baseUrl . substr($url, $pos) . $anchor;
                    }

                    return $url . $baseUrl . $anchor;
                } elseif (strncmp($url, '//', 2) === 0) {
                    if ($baseUrl !== '' && ($pos = strpos($url, '/', 2)) !== false) {
                        return substr($url, 0, $pos) . $baseUrl . substr($url, $pos) . $anchor;
                    }

                    return $url . $baseUrl . $anchor;
                }

                $url = ltrim($url, '/');
                return "$baseUrl/{$url}{$anchor}";
            }

            if ($this->suffix !== null) {
                $route .= $this->suffix;
            }
            if (!empty($params) && ($query = http_build_query($params)) !== '') {
                $route .= '?' . $query;
            }

            $route = ltrim($route, '/');
            return "$baseUrl/{$route}{$anchor}";
        }

        $url = "$baseUrl?{$this->routeParam}=" . urlencode($route);
        if (!empty($params) && ($query = http_build_query($params)) !== '') {
            $url .= '&' . $query;
        }

        return $url . $anchor;
    }
    
    private function replace($alia, $path) {
        list($route, $params) = $this->spreadUrl($path);
        $params = $this->replaceSlugCreate($params);
        
        foreach ($params as $key => $param) {
            $alia = str_replace('<'.$key.'>', $param, $alia);
        }
        return $alia;
    }
    
    private function replaceSlugCreate($params) {
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
    
    private function replaceSlugEntry($params) {
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
