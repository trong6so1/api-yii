<?php

namespace api\modules\v1\user\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\user\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class UserController extends Controller
{
    public $modelClass = 'api\modules\v1\user\models\User';

    public function actionIndex()
    {
        $request = Yii::$app->request->getQueryParams();
        $query = $this->modelClass::find();
        $query = $this->filter($query, $request);
        $query = $this->sort($query, $request);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->getQueryParam('perPage', 10)
            ],
        ]);
        return $dataProvider->getModels();
    }

    protected function filter($query, $request)
    {
        if (!empty($request['search'])) {
            $searchKeyword = $request['search'];
            $query = $query->andWhere(['like', 'title', $searchKeyword])
                ->orWhere(['like', 'body', $searchKeyword]);
        }

        if (!empty($request['id'])) {
            $query = $query->andWhere(['id' => $request['id']]);
        }
        return $query;
    }

    protected function sort($query, $request)
    {
        $sortRequest = $request['sort'] ?? 'id';

        $sort = new Sort([
            'attributes' => [
                $sortRequest
            ]
        ]);

        return $query->orderBy($sort->orders);
    }

    public function actionView($id)
    {
        $user = $this->modelClass::findOne($id);
        if ($user) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'user' => $user
            ];
            $error = null;
            $message = 'Get user Success';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Get user Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
    }

    public function actionCreate()
    {
        $user = new $this->modelClass;
        return $user->actionCreate();
    }

    public function actionUpdate($id)
    {
        $user = $this->modelClass::findOne($id);
        if (!$user) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            return $user->actionUpdate();
        }

    }


    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $user = $this->modelClass::findOne(['username' => $username]);
        if ($user) {
            if ($user->validatePassword($password)) {
                Yii::$app->user->login($user);
                $statusCode = ApiConstant::SC_OK;
                $data = ["token" => $user->access_token];
                $error = null;
                $message = 'Login successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'Pasword is incorrect';
                $message = 'Login failed';
            }

        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Username is incorrect';
            $message = 'Login failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionDelete($id)
    {
        $user = $this->modelClass::findOne($id);
        if (!$user) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
        return $user->actionDelete();
    }


}