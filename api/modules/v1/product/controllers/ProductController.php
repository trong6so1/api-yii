<?php

namespace api\modules\v1\product\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use Yii;
class ProductController extends Controller
{
    public $modelClass = '\api\modules\v1\product\models\Product';

    public function actionIndex()
    {
        return $this->modelClass::find()->all();
    }
    public function actionView($id)
    {
        $product = $this->modelClass::findOne($id);
        if($product)
        {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'product' => $product
            ];
            $error = null;
            $message = 'Get product Success';
            return ResultHelper::build($statusCode, $data, $error, $message);
        }
        else{
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