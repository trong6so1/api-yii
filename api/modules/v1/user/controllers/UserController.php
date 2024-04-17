<?php

namespace api\modules\v1\user\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\user\models\User;
use Yii;
use yii\rest\ActiveController;

class UserController extends ActiveController
{
    public $modelClass = 'api\modules\v1\user\models\User';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['delete']);
        return $actions;
    }

    public function actionCreate()
    {
        $user = new User();
        $user->email = Yii::$app->request->post('email');
        $user->username = Yii::$app->request->post('username');
        $user->password = Yii::$app->request->post('password');
        if ($user->validate()) {
            if ($user->save()) {
                $statusCode = ApiConstant::SC_OK;
                $data = $user;
                $message = 'User created successfully';
                $error = null;
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'Failed to create user';
                $message = 'An error occurred while creating the user';
            }
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $message = 'An error occurred while creating the user';
            $error = $user->getFirstErrors();
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $user = User::findOne(['username' => $username]);
        if ($user) {
            if ($user->validatePassword($password)) {
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
        $product = User::findOne($id);
        if ($product) {
            $product->delete();
            $statusCode = ApiConstant::SC_OK;
            $data = 'Deleted post id = ' . $id . ' successfully.';
            $error = null;
            $message = 'Delete successfully';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Delete Failed';
            $message = 'Post ID not found';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

}