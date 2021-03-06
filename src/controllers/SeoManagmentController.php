<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\controllers;

use Yii;
use yii\web\Controller;
use drag947\pm\models\SeoManagmentSearch;
use drag947\pm\models\SeoManagment;
use drag947\pm\models\PageManagment;
use yii\web\NotFoundHttpException;
use yii\helpers\ArrayHelper;
/**
 * Description of SeoManagmentController
 *
 * @author ilya
 */
class SeoManagmentController extends Controller {
    
    
    public function actionIndex() {
        $searchModel = new SeoManagmentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['id' => SORT_DESC],
        ];
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }
    
    public function actionCreate() {
        $model = new SeoManagment();
        $pages = PageManagment::find()->select('id, path')->all();
        $pages = ArrayHelper::map($pages, 'id', 'path');
        
        if($model->load(Yii::$app->request->post())) {
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Meta tags created!'));
                return $this->redirect(['index']);
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        
        return $this->render('create', [
            'model'=>$model,
            'pages'=>$pages
        ]);
    }
    
    public function actionUpdate($id) {
        $model = SeoManagment::findOne((int)$id);
        if(!$model) {
            throw new NotFoundHttpException();
        }
        
        if( $model->load(Yii::$app->request->post()) ) {
            
            if($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Meta tags updated!'));
                if(Yii::$app->request->referrer) {
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        
        return $this->render('update', [
            'model' => $model
        ]);
    }
}
