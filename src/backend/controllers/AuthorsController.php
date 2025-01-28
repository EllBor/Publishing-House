<?php
namespace app\controllers;

class AuthorsController extends BaseController
{
    public $modelClass = 'app\models\Authors';
    public $searchModelClass = 'app\models\AuthorsSearch';
}