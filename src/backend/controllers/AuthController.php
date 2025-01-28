<?php

namespace app\controllers;

use Yii;
use yii\rest\Controller;
use app\models\User;
use Firebase\JWT\JWT;
use yii\web\Response;

class AuthController extends Controller {

    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');

        $user = User::findOne(['username' => $username]);

        if (!$user || !Yii::$app->security->validatePassword($password, $user->password_hash)) {
            Yii::$app->response->statusCode = 401;
            return ['success' => false, 'error' => 'Неверное имя пользователя или пароль'];
        }

        $token = $this->generateJwtToken($user);
        return ['success' => true, 'token' => $token];
    }

    public function actionUser()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return ['success' => false, 'error' => 'Пользователь не аутентифицирован'];
        } else {
            return ['success' => true, 'username' => Yii::$app->user->identity->username];
        }
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();
        Yii::$app->response->statusCode = 200;
        return ['success' => true, 'message' => 'Вы успешно вышли из системы'];
    }

    public function actionRefreshToken()
    {
        $refreshToken = Yii::$app->request->post('refreshToken');
        
        try {
            $jwtSecretKey = Yii::$app->params['jwtSecretKey'];

            $decoded = JWT::decode($refreshToken, $jwtSecretKey, (object)['alg' => 'HS256']);

            if ($decoded->exp < time()) {
                Yii::$app->response->statusCode = 401;
                return ['success' => false, 'error' => 'Токен обновления истек'];
            }
            
            $user = User::findOne($decoded->user_id);
            $token = $this->generateJwtToken($user);
            return ['success' => true, 'token' => $token];

        } catch (\Exception $e) {
            Yii::$app->response->statusCode = 401;
            return ['success' => false, 'error' => 'Неверный обновляющий токен'];
        }
    }

    public function actionRegister()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
    
        if (User::findOne(['username' => $username])) {
            Yii::$app->response->statusCode = 400; 
            return ['success' => false, 'error' => 'Пользователь с таким именем уже существует'];
        }
    
        $user = new User();
        $user->username = $username;
        $user->password_hash = Yii::$app->security->generatePasswordHash($password);
    
        if ($user->save()) {
            $token = $this->generateJwtToken($user);
            return ['success' => true, 'token' => $token];
        } else {
            Yii::$app->response->statusCode = 500; 
            return ['success' => false, 'error' => 'Ошибка при создании пользователя'];
        }
    }

    protected function generateJwtToken($user)
    {
        $jwtSecretKey = Yii::$app->params['jwtSecretKey']; 

        $payload = [
            'user_id' => $user->id,
            'username' => $user->username,
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, $jwtSecretKey, 'HS256');
    }

    public function actionGenerateSecretKey()
    {
        $secretKey = Yii::$app->security->generateRandomString(32);
        Yii::$app->params['jwtSecretKey'] = $secretKey; 
        return $secretKey;
    }
}