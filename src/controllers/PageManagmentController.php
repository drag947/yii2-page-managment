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
use drag947\pm\models\PageManagment;
use drag947\pm\models\PageManagmentSearch;
use drag947\pm\models\SeoManagment;
use yii\web\NotFoundHttpException;
use drag947\pm\models\PmAlias;
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
        var_dump($page->path);
        if($page->load(Yii::$app->request->post())) {
            if( $page->save() ) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Updated!'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        
        return $this->render('view', [
            'page' => $page,
            'type' => 'page',
            'realPage' => ''
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
        
        $model = $this->findAlias((int)$alias_id);
        
        $page = $this->findModel($model->page_id);
        if( $model->load(Yii::$app->request->post()) ) {
            
            if( $model->save() ) {
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Alias created!'));
            }else{
                Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
            }
        }
        return $this->render('alias-update', [
            'model' => $model,
            'page'  => $page
        ]);
    }
    
    public function actionAliasDelete($id) {
        $model = $this->findAlias($id);
                
        if( $model->delete() ) {
            Yii::$app->session->setFlash('success', Yii::t('backend', 'Alias deleted!'));
        }else{
            Yii::$app->session->setFlash('error', Yii::t('backend', 'An error has occurred!'));
        }
        return $this->redirect(['alias', 'id'=>$model->page_id]);
    }
    
    private function findAlias($id) {
        $model = PmAlias::findOne((int)$id);
        if(!$model) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    
    public function actionSlug() {
        
    }
    
    public function actionMetaTags($id, $lang = false) {
        $page = $this->findModel($id);
        
        if($lang) {
            $model = SeoManagment::findOne(['page_id'=>$id, 'lang'=>$lang]);
        }else{
            $model = SeoManagment::findOne(['page_id'=>$id]);
        }
        
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
