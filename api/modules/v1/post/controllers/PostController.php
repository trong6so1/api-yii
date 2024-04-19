<?php

namespace api\modules\v1\post\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;

class PostController extends Controller
{
    public $modelClass = '\api\modules\v1\post\models\Post';

    public function actionIndex()
    {
        return $this->modelClass::find()->all();
    }
    public function actionView($id)
    {
        $post = $this->modelClass::findOne($id);
        if ($post) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'Post' => $post
            ];
            $error = null;
            $message = 'Get post Success';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Get post Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
    }

    public function actionCreate()
    {
        $post = new $this->modelClass;
        return $post->actionCreate();
    }

    public function actionUpdate($id)
    {
        $post = $this->modelClass::findOne($id);
        if (!$post) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            return $post->actionUpdate();
        }

    }

    public function actionDelete($id)
    {
        $post = $this->modelClass::findOne($id);
        if (!$post) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
        return $post->actionDelete();
    }

}