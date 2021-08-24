<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use backend\modules\pm\models\PageManagment;
use backend\modules\pm\models\PageManagmentSearch;
use backend\modules\pm\models\SeoManagment;
use yii\web\NotFoundHttpException;
use backend\modules\pm\models\PmAlias;
/**
 * Description of PageManagmentController
 *
 * @author ilya
 */
class PageManagmentController extends Controller {
    
    
    
    public function actionIndex() {
        $searchModel = new PageManagmentSearch();
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
        $model = new PageManagment();
        $modelMeta = new SeoManagment();
        $transaction = Yii::$app->db->beginTransaction();
        if($model->load(Yii::$app->request->post())) {
            try {
                if( $model->save() ) {
                    $modelMeta->page_id = $model->id;
                    $modelMeta->lang = Yii::$app->language;
                    if( $modelMeta->save() ) {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', Yii::t('backend', 'Page created!'));
                        return $this->redirect(['index']);
                    }else{
                        Yii::$app->session->setFlash('error', $model->errors);
                    }
                }else{
                    Yii::$app->session->setFlash('error', $modelMeta->errors);
                }
                $transaction->rollBack();
            } catch (\Exception $ex) {
                Yii::error($ex->getMessage(), 'pm');
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
                $transaction->rollBack();
            }
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
    
    public function actionView($id) {
        $page = $this->findModel($id);
        
        return $this->render('view', [
            'page' => $page,
            'type' => 'page'
        ]);
    }
    
    public function actionAlias($id) {
        $page = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => PmAlias::find()->where(['page_id'=>(int)$id])
        ]);
        
        return $this->render('view', [
            'dataProvider' => $dataProvider,
            'type' => 'alias',
            'page' => $page
        ]);
    }
    
    public function actionAliasCreate($id) {
        $page = $this->findModel($id);
        $model = new PmAlias();
        if( $model->load(Yii::$app->request->post()) ) {
            $model->page_id = $page->id;
            if( $model->save() ) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Alias created!'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        return $this->render('alias-create', [
            'model' => $model,
            'page'  => $page
        ]);
    }
    
    public function actionAliasUpdate($alias_id) {
        
        $model = PmAlias::findOne((int)$alias_id);
        if(!$model) {
            throw new NotFoundHttpException();
        }
        $page = $this->findModel($model->id);
        if( $model->load(Yii::$app->request->post()) ) {
            
            if( $model->save() ) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Alias created!'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        return $this->render('alias-create', [
            'model' => $model,
            'page'  => $page
        ]);
    }
    
    public function actionMetaTags($id) {
        $page = $this->findModel($id);
        
        $model = SeoManagment::findOne(['page_id'=>$id, 'lang'=>Yii::$app->language]);
        
        return $this->render('view', [
            'model' => $model,
            'page'  => $page,
            'type'  => 'meta_tags'
        ]);
    }
    
    public function findModel($id) {
        $model = PageManagment::findOne((int)$id);
        if(!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}