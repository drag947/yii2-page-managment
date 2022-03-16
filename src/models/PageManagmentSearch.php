<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use drag947\pm\models\PageManagment;
/**
 * Description of SearchModel
 *
 * @author ilya
 */
class PageManagmentSearch extends PageManagment {
    public $service;
    public $path;
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['path'], 'string', 'max'=>255],
            ['path', 'trim']
        ];
    }
    
    public function attributeLabels() {
        return [
            'path' => Yii::t('pm', 'Path')
        ];
    }

    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PageManagment::find()->where(['is_group' => 0])->alias('pm');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'pm.id' => $this->id,
        ]);
        
        $service = Yii::$app->controller->module->getUrlService();
        
        list($route, $params) = $service->findRealUrlPage($this->path);
        if($route) {
            $query->andFilterWhere(['LIKE', 'pm.route', $route]);
        }
        
        if($params) {
            $query->leftJoin(PageParams::tableName().' as pp', 'pm.id = pp.page_id');
            $params = $service->replaceSlugReverse($params);
            
            foreach ($params as $param => $value) {
                $query->andFilterWhere(['pp.param' => $param, 'pp.value' => $value]);
            }
        }

        return $dataProvider;
    }
}
