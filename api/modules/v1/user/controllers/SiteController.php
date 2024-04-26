<?php

namespace api\modules\v1\user\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\User\models\search\SearchIndex;
use api\modules\v1\User\models\User;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;

class SiteController extends Controller
{
    public function actionIndex(): array
    {
        $users = SearchIndex::search(Yii::$app->request->queryParams);
        $statusCode = ApiConstant::SC_OK;
        $data = [
            'users' => $users,
        ];
        $error = null;
        $message = "Get all users Successfully";
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionView($id): array
    {
        $user = User::findOne($id);
        if ($user) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'user' => $user
            ];
            $error = null;
            $message = 'Get user Success';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Get user Failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionCreate(): array
    {
        $user = new User();
        $request = Yii::$app->request->post();
        $user->username = $request['username'];
        $user->email = $request['email'];
        $user->password = $request['password'];
        if ($user->validate()) {
            if ($user->save()) {
                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole('author');
                $auth->assign($authorRole, $user->getId());
                $statusCode = ApiConstant::SC_OK;
                $data = ['user' => $user];
                $error = null;
                $message = 'Create user Successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during creating the user';
                $message = 'Create user Failed';
            }
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $user->getErrors();
            $message = 'Create user Failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     */
    public function actionUpdate($id): array
    {
        $user = User::findOne($id);
        if (!$user) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Updated Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            if ($user->load(Yii::$app->request->post(), '') && $user->validate()) {
                if ($user->save()) {
                    $statusCode = ApiConstant::SC_OK;
                    $data = ['user' => $user];
                    $error = null;
                    $message = 'Update user Successfully';
                } else {
                    $statusCode = ApiConstant::SC_BAD_REQUEST;
                    $data = null;
                    $error = 'There was an error during updating the user';
                    $message = 'Update user Failed';
                }
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = $user->getFirstErrors();
                $message = 'Update user Failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionLogin(): array
    {
        $request = Yii::$app->request->post();
        $username = $request['username'];
        $password = $request['password'];
        $user = User::findOne(['username' => $username]);
        if ($user) {
            if ($user->validatePassword($password)) {
                $statusCode = ApiConstant::SC_OK;
                $data = ["token" => $user->getAccessToken()];
                $error = null;
                $message = 'Login successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'Password is incorrect';
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

    /**
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id): array
    {
        $user = User::findOne($id);
        if (!$user) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'User ID not found';
            $message = 'Delete Failed';
        } else {
            $user->delete();
            if ($user->isDeleted) {
                $statusCode = ApiConstant::SC_OK;
                $data = [
                    'User ID' => $id
                ];
                $error = null;
                $message = 'Delete successfully successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during deletion';
                $message = 'Delete Failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionLogout(): array
    {
        if (Yii::$app->user->logout()) {
            $statusCode = ApiConstant::SC_OK;
            $data = null;
            $error = null;
            $message = 'Logout successfully';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'There was an error during logout';
            $message = 'Logout Failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
}