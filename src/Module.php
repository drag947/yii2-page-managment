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
    
    public $urlService;
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        
    }
    
    public function getUrlService() : UrlService {
        if(!$this->urlService) {
            $this->urlService = new UrlService();
        }
        return $this->urlService;
    }
    
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['pm'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'basePath'       => '@vendor/drag947/yii2-page-managment/src/messages',
            'fileMap'        => [
                'pm' => 'pm.php'
            ]
        ];
    }
    
}
