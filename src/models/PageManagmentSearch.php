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
    
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['path'], 'string', 'max'=>255]
        ];
    }


    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PageManagment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere(['LIKE', 'path', $this->path]);


        return $dataProvider;
    }
}
