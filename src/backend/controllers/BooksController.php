<?php

namespace app\controllers;

class BooksController extends BaseController
{
    public $modelClass = 'app\models\Books';
    public $searchModelClass = 'app\models\BooksSearch';
}
