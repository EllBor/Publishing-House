<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Yii;

class AuthorsSearch extends Authors
{
    public $selectedFirst;
    public $selectedLast;
    public $per_page;
    public $page;
    
    public function rules()
    {
        return [
            [['selectedLast', 'selectedFirst', 'per_page', 'page'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Authors::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!empty($this->selectedFirst)) {
            $query->andFilterWhere(['like', 'firstName', $this->selectedFirst]);
        }

        if (!empty($this->selectedLast)) {
            $query->andFilterWhere(['like', 'lastName', $this->selectedLast]);
        }
        
        return $dataProvider;
    }
}
