<?php

namespace app\models;

use yii\data\ActiveDataProvider;
use Yii;

class BooksSearch extends Books
{
    public $selectedISBN;
    public $selectedNameBook;
    public $selectedNumberOfPages;
    public $selectedDateOfPublication;
    public $per_page;
    public $page;

    public function rules()
    {
        return [
            [['selectedNumberOfPages'], 'integer'],
            ['selectedDateOfPublication', 'date', 'format' => 'php:Y-m-d'],
            [['selectedISBN', 'selectedNameBook', 'selectedNumberOfPages', 'selectedDateOfPublication', 'per_page', 'page'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Books::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, '');

        if (!empty($this->selectedISBN)) $query->andFilterWhere(['like', 'ISBN', $this->selectedISBN]);
        if (!empty($this->selectedNameBook))  $query->andFilterWhere(['like', 'nameBook', $this->selectedNameBook]);
        if (!empty($this->selectedNumberOfPages)) $query->andFilterWhere(['=', 'numberOfPages', $this->selectedNumberOfPages]);
        if (!empty($this->selectedDateOfPublication)) $query->andFilterWhere(['=', 'dateOfPublication', $this->selectedDateOfPublication]);

        return $dataProvider;
    }
}
