<?php

namespace api\modules\v1\post\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\post\models\Post;
use yii\rest\ActiveController;

class PostController extends ActiveController
{
    public $modelClass = '\api\modules\v1\post\models\Post';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function actionDelete($id)
    {
        $post = Post::findOne($id);
        if ($post) {
            $post->delete();
            $statusCode = ApiConstant::SC_OK;
            $data = 'Deleted post id = ' . $id . ' successfully.';
            $error = null;
            $message = 'Deleted Post successfully.';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Delete Failed';
            $message = 'Post ID not found';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

}