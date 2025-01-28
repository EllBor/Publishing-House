<?php

namespace app\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\web\Response;
use yii\filters\Cors;

class BaseController extends ActiveController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator']['formats']['application/json'] = Response::FORMAT_JSON;

        $behaviors['corsFilter'] = [
            'class' => Cors::class,
            'cors' => [
                'Origin' => ['http://localhost:3000', 'http://localhost:3000'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
                'Access-Control-Max-Age' => 3600,
                'Access-Control-Expose-Headers' => ['*'],
            ],
        ];

        return $behaviors;
    }

    public function actionOptions()
    {
        Yii::$app->response->statusCode = 200;
    }

    public function actions()
    {
        $actions = parent::actions();

        $actions['index'] = [
            'class' => 'yii\rest\IndexAction',
            'modelClass' => $this->modelClass,
            'prepareDataProvider' => [$this, 'prepareDataProvider'],
        ];

        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModelClass = $this->searchModelClass;
        $searchModel = new $searchModelClass();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $dataProvider->pagination->pageSize = Yii::$app->request->get('per_page', $searchModel->per_page);
        $dataProvider->pagination->page = Yii::$app->request->get('page', $searchModel->page);
        Yii::$app->response->headers->set('X-Pagination-Total-Count', $dataProvider->getTotalCount());

        return $dataProvider;
    }

    protected function actionView($id)
    {
        $modelClass = $this->modelClass;
        $id = Yii::$app->request->get('id');
        $model = $modelClass::findOne($id);

        if ($model) {
            Yii::$app->response->statusCode = 200;
            return ['success' => true, 'data' => $model];
        } else {
            Yii::$app->response->statusCode = 404;
            return ['success' => false, 'error' => $this->modelClass . ' not found.'];
        }
    }

    protected function actionCreate()
    {
        $modelClass = $this->modelClass;
        $model = new $modelClass();

        if ($model->load(Yii::$app->request->bodyParams) && $model->save()) {
            Yii::$app->response->statusCode = 201;
            return ['success' => true, 'data' => $model];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['success' => false, 'error' => $model->errors];
        }
    }

    protected function actionUpdate($id)
    {
        $modelClass = $this->modelClass;
        $id = Yii::$app->request->get('id');
        $model = $modelClass::findOne($id);

        if (!$model) {
            Yii::$app->response->statusCode = 404;
            return ['success' => false, 'error' => $this->modelClass . ' not found'];
        }

        if ($model->load(Yii::$app->request->bodyParams) && $model->save()) {
            Yii::$app->response->statusCode = 200;
            return ['success' => true, 'data' => $model];
        } else {
            Yii::$app->response->statusCode = 400;
            return ['success' => false, 'error' => $model->errors];
        }
    }

    protected function actionDelete($id)
    {
        $modelClass = $this->modelClass;
        $id = Yii::$app->request->get('id');
        $model = $modelClass::findOne($id);

        if ($model) {
            $model->delete();
            Yii::$app->response->statusCode = 200;
            return ['success' => true];
        } else {
            Yii::$app->response->statusCode = 404;
            return ['success' => false];
        }
    }
}
