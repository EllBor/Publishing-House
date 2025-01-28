<?php

namespace app\models;

use yii\data\ActiveDataProvider;

class GenresAuthorsSearch extends Books
{
    public $selectedGenre;
    public $selectedAuthor;
    public $per_page;
    public $page;

    public function rules()
    {
        return [
            [['selectedGenre', 'selectedAuthor', 'per_page', 'page'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Books::find()
            ->select([
                'books.*',
                'GROUP_CONCAT(DISTINCT CONCAT(authors.firstName, " ", authors.lastName) SEPARATOR ", ") AS authorNames',
                "GROUP_CONCAT(DISTINCT genres.nameOfGenres SEPARATOR ', ') AS genreNames",
            ])
            ->joinWith([
                'authors',
                'genres'
            ])
            ->groupBy('books.id');

        $this->load($params, '');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($this->selectedGenre)) {
            $query->andFilterWhere(['like', 'genres.nameOfGenres', $this->selectedGenre]);
        }

        if (!empty($this->selectedAuthor)) {
            $query->andFilterWhere(['like', 'authors.lastName', $this->selectedAuthor]);
        }

        return $dataProvider;
    }
}
