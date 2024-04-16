<?php

namespace api\modules\v1\users\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResponseHelper;
use api\helper\response\ResultHelper;
use api\modules\v1\products\models\Products;
use api\modules\v1\users\controllers\Controller;
use api\modules\v1\users\models\Users;
use Yii;
class UsersController extends Controller
{
    public $modelClass = 'api\modules\v1\users\models\Users';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create'],$actions['delete']);
        return $actions;
    }

    public function actionCreate()
    {
        $user = new Users();
        $user->status = 10;
        $user->email = Yii::$app->request->post('email');
        $user->username = Yii::$app->request->post('username');
//        $user->password = Yii::$app->request->post('password');
        if($user->validate()) {
            if ($user->save()) {
                $status = ApiConstant::SC_OK;
                $data = $user;
                $message = 'User created successfully';
                $error = null;
            } else {
                $status = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'Failed to create user';
                $message = 'An error occurred while creating the user';
            }
        }
        else{
            $status = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $message = 'An error occurred while creating the user';
            $error = $user->getFirstErrors();
        }
        return ResultHelper::build($status, $data, $message, $error);
    }

    public function actionLogin()
    {
        $username = Yii::$app->request->post('username');
        $password = Yii::$app->request->post('password');
        $user = Users::findOne(['username' => $username]);
        if($user)
        {
            if($user->validatePassword($password))
            {
                return ResultHelper::build(
                    ApiConstant::SC_OK,
                    ["token" => $user->access_token],
                    null,
                    'Login successfully'
                );
            }
            else{
                $message = 'Pasword is incorrect';
            }

        }
        else{
            $message = 'Username is incorrect';
        }
        return ResultHelper::build(
            ApiConstant::SC_OK,
            $message,
            "Login failed");
    }

    public function actionDelete($id)
    {
        $product = Users::findOne($id);
        if ($product) {
            $product->delete();
            return ResultHelper::build(ApiConstant::SC_OK,
                'Deleted post id = ' . $id . ' successfully.',
                null,
                'Delete successfully'
            );
        } else {
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Delete Failed',
                'Post ID not found'
            );
        }
    }

}