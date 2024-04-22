<?php

namespace api\modules\v1\post\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\data\Sort;

class PostController extends Controller
{
    public $modelClass = '\api\modules\v1\post\models\Post';

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
            $query = $query->andWhere(['like', 'username', $searchKeyword])
                ->orWhere(['like', 'email', $searchKeyword]);
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