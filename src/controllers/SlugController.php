<?php

namespace drag947\pm\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use drag947\pm\models\PmSlugs;
use yii\web\NotFoundHttpException;
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
    
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $service = $this->module->getUrlService();
        if($model->load(Yii::$app->request->post()) && $model->validate() ) {
            $service->createSlug($model);
            //Yii::$app->session->setFlash('success', Yii::t('pm', 'Slug created'));
        }
        
        if($model->hasErrors()) {
            foreach ($model->getFirstErrors() as $error) {
                Yii::$app->session->addFlash('error', $error);
            }
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
    
    public function actionCreate() {
        $service = $this->module->getUrlService();
        
        $model = new PmSlugs();
        
        if($model->load(Yii::$app->request->post()) && $model->validate() ) {
            $service->createSlug($model);
            //Yii::$app->session->setFlash('success', Yii::t('pm', 'Slug created'));
        }
        
        if($model->hasErrors()) {
            foreach ($model->getFirstErrors() as $error) {
                Yii::$app->session->addFlash('error', $error);
            }
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id) {
        $service = $this->module->getUrlService();
        $model = $this->findModel($id);
        $service->deleteSlug($model);
        Yii::$app->session->setFlash('success', Yii::t('pm', 'Slug deleted'));
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    private function findModel($id) {
        $model = PmSlugs::findOne($id);
        if(!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}
