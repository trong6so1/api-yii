<?php

namespace api\modules\v1\product\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class ProductController extends Controller
{
    public $modelClass = '\api\modules\v1\product\models\Product';

    public function actionIndex()
    {
        $request = Yii::$app->request->getQueryParams();
        $query = $this->modelClass::find();
        $query = $this->filter($query, $request);
        $query = $this->sort($query, $request);
        $dataProvider =
            new ActiveDataProvider([
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
                ->orWhere(['like', 'description', $searchKeyword]);
        }
        if (!empty($request['id'])) {
            $query = $query->andWhere(['id' => $request['id']]);
        }
        if (!empty($request['stock'])) {
            $query = $query->andWhere(['>=', 'stock', $request['stock']]);
        }
        if (!empty($request['price'])) {
            $query = $query->andWhere(['>=', 'price', $request['stock']]);
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
        $product = $this->modelClass::findOne($id);
        if ($product) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'product' => $product
            ];
            $error = null;
            $message = 'Get product Success';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Product ID not found';
            $message = 'Get product Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
    }

    public function actionCreate()
    {
        $product = new $this->modelClass;
        return $product->actionCreate();
    }

    public function actionUpdate($id)
    {
        $product = $this->modelClass::findOne($id);
        if (!$product) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Product ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        } else {
            return $product->actionUpdate();
        }

    }

    public function actionDelete($id)
    {
        $product = $this->modelClass::findOne($id);
        if (!$product) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Product ID not found';
            $message = 'Delete Failed';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
        return $product->actionDelete();
    }
}