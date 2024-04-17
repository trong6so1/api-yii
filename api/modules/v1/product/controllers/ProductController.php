<?php

namespace api\modules\v1\product\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\product\models\Product;
use yii\rest\ActiveController;

class ProductController extends ActiveController
{
    public $modelClass = '\api\modules\v1\product\models\Product';

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function actionDelete($id)
    {
        $product = Product::findOne($id);
        if ($product) {
            $product->delete();
            $statusCode = ApiConstant::SC_OK;
            $data = 'Deleted product ID = ' . $id . ' successfully.';
            $error = null;
            $message = 'Delete successfully';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Delete Failed';
            $message = 'Product ID not found';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
}