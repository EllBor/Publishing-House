<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Yii;

class GenresSearch extends Genres
{
    public $selectedNameOfGenres;
    public $per_page;
    public $page;

    public function rules()
    {
        return [
            [['selectedNameOfGenres', 'per_page', 'page'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Genres::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!empty($this->selectedNameOfGenres)) {
            $query->andFilterWhere(['like', 'nameOfGenres', $this->selectedNameOfGenres]);
        }

        return $dataProvider;
    }
}
