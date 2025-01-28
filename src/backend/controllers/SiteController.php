<?php

namespace app\controllers;

use Yii;
use app\models\Genres;
use app\models\Authors;
use app\models\GenresAuthorsSearch;

class SiteController extends BaseController
{
    public $modelClass = 'app\models\Books';
    public $searchModelClass = 'app\models\GenresAuthorsSearch';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['view']);
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new GenresAuthorsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $topAuthors = Authors::find()
            ->select([
                'authors.id',
                'CONCAT(authors.firstName, " ", authors.lastName) AS fullName',
            ])
            ->joinWith('books')
            ->groupBy(['authors.id'])
            ->orderBy(['COUNT(books_authors.book_id)' => SORT_DESC])
            ->limit(3)
            ->asArray()
            ->all();

        $topGenres = Genres::find()
            ->select([
                'genres.id',
                'nameOfGenres',
            ])
            ->joinWith('books')
            ->groupBy(['genres.id'])
            ->orderBy(['COUNT(books_genres.book_id)' => SORT_DESC])
            ->limit(3)
            ->asArray()
            ->all();

        $data = [
            'dataProvider' => $dataProvider,
            'topAuthors' => $topAuthors,
            'topGenres' => $topGenres,
        ];

        return $data;
    }
}
