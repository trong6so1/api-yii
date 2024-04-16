<?php

namespace api\modules\v1\posts\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\posts\controllers\Controller;
use api\modules\v1\posts\models\Posts;

class PostsController extends Controller
{
    public $modelClass = '\api\modules\v1\posts\models\Posts';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }
    public function actionDelete($id)
    {
        $post = Posts::findOne($id);
        if ($post)
        {
            $post->delete();
            return  ResultHelper::build(ApiConstant::SC_OK,
                'Deleted post id = ' . $id .' successfully.'
            );
        }
        else{
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Delete Failed',
                'Post ID not found'
            );
        }
    }

}