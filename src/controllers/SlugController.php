<?php

namespace drag947\pm\controllers;

use yii\data\ActiveDataProvider;
use drag947\pm\models\PmSlugs;
/**
 * Description of SlugController
 *
 * @author ilya
 */
class SlugController extends \yii\web\Controller {
    
    
    public function actionIndex() {
        
        $dataProvider = new ActiveDataProvider([
            'query' => PmSlugs::find()
        ]);
        
        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
}
