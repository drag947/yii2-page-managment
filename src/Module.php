<?php

namespace drag947\pm;

use Yii;
/**
 * article module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'drag947\pm\controllers';
    
    private $urlService;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        
    }
    
    public function getUrlService() : UrlService {
        if(!$this->urlService) {
            $this->urlService = new UrlService(Yii::$app->db);
        }
        return $this->urlService;
    }
    
}
