<?php

namespace api\modules\v1\product\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\product\models\product;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;

class SiteController extends Controller
{
    public function actionIndex(): array
    {
        $products = Product::find()->all();
        $statusCode = ApiConstant::SC_OK;
        $data = ['products' => $products];
        $error = null;
        $message = 'Get all products successfully';
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionView($id): array
    {
        $product = product::findOne($id);
        if ($product) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'product' => $product
            ];
            $error = null;
            $message = 'Get product Success';
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'product ID not found';
            $message = 'Get product Failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $product = new product();
        $product->title = Yii::$app->request->post('title');
        $product->description = Yii::$app->request->post('description');
        $product->price = Yii::$app->request->post('price');
        $product->stock = Yii::$app->request->post('stock');
        $product->category = Yii::$app->request->post('category');

        if ($product->validate()) {
            if ($product->save()) {
                $statusCode = ApiConstant::SC_OK;
                $data = ['product' => $product];
                $error = null;
                $message = 'Create product successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during creating the product';
                $message = 'Create product failed';
            }
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $product->getFirstErrors();
            $message = 'Create product failed';
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    /**
     * @throws Exception
     */
    public function actionUpdate($id): array
    {
        $product = product::findOne($id);

        if (!$product) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'product ID not found';
            $message = 'Update product Failed';
        } else {
            $product->title = Yii::$app->request->post('title') ?? $product->title;
            $product->description = Yii::$app->request->post('description') ?? $product->description;
            $product->price = Yii::$app->request->post('price') ?? $product->price;
            $product->stock = Yii::$app->request->post('stock') ?? $product->stock;
            $product->category = Yii::$app->request->post('product') ?? $product->category;

            if ($product->validate()) {
                if ($product->save()) {
                    $statusCode = ApiConstant::SC_OK;
                    $data = ['product' => $product];
                    $error = null;
                    $message = 'Update product successfully';
                } else {
                    $statusCode = ApiConstant::SC_BAD_REQUEST;
                    $data = null;
                    $error = 'There was an error during creating the product';
                    $message = 'Update product Failed';
                }
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = $product->getFirstErrors();
                $message = 'Update product Failed';
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
        $product = product::findOne($id);
        if (!$product) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'product ID not found';
            $message = 'Delete product failed';
        } else {
            $product->delete();
            if ($product->isDeleted) {
                $statusCode = ApiConstant::SC_OK;
                $data = ['id' => $id];
                $error = null;
                $message = 'Deleted product successfully';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'There was an error during deletion';
                $message = 'Deleted product failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
}