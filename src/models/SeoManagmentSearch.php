<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\modules\pm\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;
use backend\modules\pm\models\SeoManagment;
/**
 * Description of SearchModel
 *
 * @author ilya
 */
class SeoManagmentSearch extends SeoManagment {
    
    
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'description', 'h_one'], 'string', 'max'=>255]
        ];
    }


    /**
     * Creates data provider instance with search query applied
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SeoManagment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);
        $query->andFilterWhere(['LIKE', 'title', '%'.$this->title.'%']);
        $query->andFilterWhere(['LIKE', 'description', '%'.$this->description.'%']);
        $query->andFilterWhere(['LIKE', 'h_one', '%'.$this->h_one.'%']);

        return $dataProvider;
    }
}
