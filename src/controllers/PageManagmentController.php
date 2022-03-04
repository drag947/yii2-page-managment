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
use yii\base\DynamicModel;
use richardfan\sortable\SortableAction;
/**
 * Description of PageManagmentController
 *
 * @author ilya
 */
class PageManagmentController extends Controller {
    
    private $service;
    
    public function __construct($id, $module, $config = array()) {
        parent::__construct($id, $module, $config);
        $this->service = $this->module->getUrlService();
    }
    
    public function actions() {
        return [
            'sort-alias' => [
                'class' => SortableAction::class,
                'activeRecordClassName' => PmAlias::class,
                'orderColumn' => 'sort',
            ],
        ];
    }
    
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
    
    public function actionCreatePossiblePages() {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $count = $this->service->createPossiblePages();
            $transaction->commit();
             Yii::$app->session->setFlash('info', 'Create pages count: '.$count);
        } catch (drag947\pm\MessageException $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            $transaction->rollBack();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
       
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    public function actionCreate() {
        $model = $this->formPage();
        $transaction = Yii::$app->db->beginTransaction();
        if($model->load(Yii::$app->request->post())) {
            try {
                $this->module->getUrlService()->createPage($model->path);
                $transaction->commit();
                Yii::$app->session->setFlash('success', Yii::t('backend', 'Page created!'));
                return $this->redirect(['index']);
                
            }catch (\drag947\pm\MessageException $ex) {
                Yii::$app->session->setFlash('error', $ex->getMessage());
                $transaction->rollBack();
            } catch (\Exception $ex) {
                throw $ex;
                $transaction->rollBack();
            }
        }
        
        return $this->render('create', [
            'model' => $model
        ]);
    }
    
    public function actionView($id) {
        $page = $this->findModel($id);
        if($page->load(Yii::$app->request->post()) && $page->validate()) {
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
    
    public function actionDelete($id) {
        $page = $this->findModel($id);
        $page->delete();
        Yii::$app->session->setFlash('success', Yii::t('backend', 'Delete!'));
        return $this->redirect(Yii::$app->request->referrer);
    }
    
    private function formPage($insert = true) {
        $model = new DynamicModel();
        $model->defineAttribute('path');
        $model->defineAttribute('isNewRecord', $insert);
        $model->addRule('path', 'required');
        return $model;
    }
    
    public function actionAlias($id) {
        $page = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => PmAlias::find()->where(['page_id'=>(int)$id])
        ]);
        $dataProvider->sort = [
            'defaultOrder' => ['sort' => SORT_ASC],
        ];
        
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
