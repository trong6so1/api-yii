<?php

namespace api\modules\v1\post\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\post\models\Post;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;

class SiteController extends Controller
{
    public function actionIndex(): array
    {
        $posts = Post::find()->all();
        $statusCode = ApiConstant::SC_OK;
        $data = ['posts' => $posts];
        $error = null;
        $message = 'Get all posts successfully';
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionView($id): array
    {
        $post = Post::findOne($id);
        if ($post) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'Post' => $post
            ];
            $error = null;
            $message = 'Get post Success';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Get post Failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $post = new Post();
        $post->title = Yii::$app->request->post('title');
        $post->body = Yii::$app->request->post('body');
        $post->tags = Yii::$app->request->post('tags');

        if ($post->validate()) {
            if ($post->save()) {
                $statusCode = ApiConstant::SC_OK;
                $data = ['post' => $post];
                $error = null;
                $message = 'Create post successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during creating the post';
                $message = 'Create post failed';
            }
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $post->getFirstErrors();
            $message = 'Create post failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     */
    public function actionUpdate($id): array
    {
        $post = Post::findOne($id);

        if (!$post) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Update post Failed';
        } else {
            $post->title = Yii::$app->request->post('title') ?? $post->title;
            $post->body = Yii::$app->request->post('body') ?? $post->body;
            $post->tags = Yii::$app->request->post('tags') ?? $post->tags;
            if ($post->validate()) {
                if ($post->save()) {
                    $statusCode = ApiConstant::SC_OK;
                    $data = ['post' => $post];
                    $error = null;
                    $message = 'Update post successfully';
                } else {
                    $statusCode = ApiConstant::SC_BAD_REQUEST;
                    $data = null;
                    $error = 'There was an error during creating the post';
                    $message = 'Update Post Failed';
                }
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = $post->getFirstErrors();
                $message = 'Update post Failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     */
    public function actionDelete($id): array
    {
        $post = Post::findOne($id);
        if (!$post) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Post ID not found';
            $message = 'Delete post failed';
        } else {
            $post->delete();
            if ($post->isDeleted) {
                $statusCode = ApiConstant::SC_OK;
                $data = ['id' => $id];
                $error = null;
                $message = 'Deleted post successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during deletion';
                $message = 'Deleted post failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

}