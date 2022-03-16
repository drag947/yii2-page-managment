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
use yii\helpers\Inflector;
use drag947\pm\MessageException;
/**
 * Description of UrlService
 *
 * @author ilya
 */
class UrlService {
    /*
     * [
     *  'name_entity' => [
     *          'class' => ActiveRecord,
     *          'field' => string,
     * 'id',
     * 'url'
     *      ]
     * ]
     */
    private $entities;
    
    private function hasEntity($key) {
        return isset($this->entities[$key]);
    }
    
    private function getEntity($key) {
        return $this->entities[$key];
    }
    
    public function __construct($entities = []) {
        $this->entities = $entities;
    }
    
    public function getGroups() {
        return PageManagment::findAll(['is_group' => 1]);
    }
    
    public function createPage(string $path, $group_id = null) {
        
        list($route, $params) = $this->findRealUrlPage($path);
        
        if(!$this->isUniquePage($route, $params)) {
            throw new MessageException('Страница с таким url уже создана');
        }
        
        $page = new PageManagment();
        $page->route = $route;
        $page->group_id = $group_id;
        $seo = new SeoManagment();
        $seo->lang = Yii::$app->language;
        
            
        if( !$page->save() ) {
            throw new PmException('page not save. path:'.$path);
        }
        
        $newParams = $this->replaceSlug($params);
        
        if($newParams !== $params) {
            foreach ($newParams as $k => $param) {
                if($param !== $params[$k]) {
                    if(PmSlugs::findOne(['param' => $k,'key' => $params[$k],'value' => $param])) {
                        continue;
                    }
                    $slug = new PmSlugs([
                        'param' => $k,
                        'key' => $params[$k],
                        'value' => $param
                    ]);
                    if(!$slug->validate()) {
                        $value = $param.$params[$k];
                        if(strlen($value) > 70) {
                            $value = substr($param, 0, strlen($param) - strlen($params[$k])).$params[$k];
                        }
                        $slug->value = $value;
                    }
                    $slug->save();
                    if($slug->hasErrors()) {
                        throw new PmException(implode('|', $slug->getFirstErrors()));
                    }
                }
            }
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
    
    public function createPossiblePages() {
        $i = 0;
        
        foreach ($this->entities as $name => $entity) {
            $rows = Yii::$app->db->createCommand("SELECT * FROM ".$entity['table'])->queryAll();
            foreach ($rows as $row) {
                $url = str_replace('<id>', $row[$entity['id']], $entity['url']);
                try {
                    $this->createPage($url);
                $i++;
                } catch (MessageException $e) {
                    
                }
            }
        }
        return $i;
    }
    
    public function countPossiblePages() {
        //return Yii::$app->db->createCommand("SELECT * FROM ")->queryAll();
    }
    
    public function createSlug($model) {
        $model->save();
        
        if($model->hasErrors()) {
            throw new MessageException(implode('|', $model->getFirstErrors()));
        }
        
        $models = $this->findPagesByParam($model->param, $model->key);
        
        foreach ($models as $model) {
            if(!$model->page->alias) {
                continue;
            }
            $alias = $model->page->alias[0];
            $newAlias = new PmAlias();
            $newAlias->url = $alias->url;
            $newAlias->page_id = $alias->page_id;
            $newAlias->save();
            
            if($newAlias->hasErrors()) {
                throw new MessageException(implode('|', $newAlias->getFirstErrors()));
            }
        }
    }
    
    public function deleteSlug($model) {
        $models = $this->findPagesByParam($model->param, $model->key);
        $model->delete();
        foreach ($models as $model) {
            if(!$model->page->alias) {
                continue;
            }
            $alias = $model->page->alias[0];
            
            $newAlias = new PmAlias();
            $newAlias->url = $alias->url;
            $newAlias->page_id = $alias->page_id;
            $alias->delete();
            $newAlias->save();
            
            if($newAlias->hasErrors()) {
                throw new MessageException(implode('|', $newAlias->getFirstErrors()));
            }
        }
    }
    
    private function findPagesByParam($param, $key) {
        return PageParams::find()->with(['page', 'page.alias' => function ($query) {
            $query->limit(1)->orderBy('id');
        }])->where(['param' => $param])->andWhere(['value' => $key])->all();
    }
    
    public function findRealUrlPage($path) {
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
        
        return true;
    }
    
    public static function replace($alia, $path) {
        list($route, $params) = $this->spreadUrl($path);
        $params = $this->replaceSlug($params);
        
        foreach ($params as $key => $param) {
            $alia = str_replace('<'.$key.'>', $param, $alia);
        }
        return $alia;
    }
    // заменяет реальное значение на слаг
    public function replaceSlug($params) {
        $result = [];
        foreach ($params as $key => $value) {
            $slug = PmSlugs::findOne(['key' => $value, 'param' => $key]);
            
            if(!$slug) {
                if($this->hasEntity($key)) {
                    $entity = $this->getEntity($key);
                    if($entity['field']) {
                        $result[$key] = $this->autoSlug($entity, $value);
                    }else{
                        $result[$key] = $value;
                    }
                    continue;
                }
                $result[$key] = $value;
                continue;
            }
            $result[$key] = $slug->value;
        }
        return $result;
    }
    // заменяет слаг на реальное значение
    public function replaceSlugReverse($params) {
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
    
    private function autoSlug($entity, $key) {
        $name = Yii::$app->db->createCommand("SELECT ".$entity['field']." FROM ".$entity['table']." WHERE ".$entity['id']." = :id LIMIT 1", [':id' => $key])->queryOne();
        if(!$name) {
            return $key;
        }
        return trim(substr(Inflector::slug($name[$entity['field']]), 0, 70), '-');
        //return Inflector::slug($entity['class']::find()->where([$entity['id'] => $value])->limit(1)->one()[$entity['field']]);
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