<?php

namespace api\modules\v1\cart\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\cart\models\Cart;
use api\modules\v1\Product\models\Product;
use Yii;

class CartController extends Controller
{
    public $modelClass = '\api\modules\v1\cart\models\Product';

    public function actions()
    {
        $action = parent::actions();
        unset($action['index'], $action['update'], $action['delete'], $action['view']);
        return $action;
    }

    public function actionIndex()
    {
        $user = Yii::$app->user->getId();
        $cart = Cart::findAll(['user_id' => $user]);
        return $cart;
    }

    public function actionAdd()
    {
        $product = Product::findOne(['id' => Yii::$app->request->post('product_id')]);
        if (empty($product)) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Product not found';
            $message = 'Add Failed';
        } else {
            $user = Yii::$app->user->getId();
            $quantity = Yii::$app->request->post('quantity');
            if (!$quantity || $quantity < 1) {
                $quantity = 1;
            }
            $cart = Cart::findOne(['user_id' => $user, 'product_id' => $product->id]);
            if ($cart) {
                $cart->quantity += $quantity;
                $cart->save();
            } else {
                $cart = new Cart();
                $cart->product_id = $product->id;
                $cart->quantity = $quantity;
                $cart->user_id = $user;
                $cart->save();
            }
            $statusCode = ApiConstant::SC_OK;
            $data = 'Add Product ID: ' . $product->id . ' successfully';
            $message = 'Add Success';
            $error = null;
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
    
    public function actionLess()
    {
        $product = Product::findOne(['id' => Yii::$app->request->post('product_id')]);
        if (empty($product)) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $error = 'Product not found';
            $message = 'Less Failed';
            $data = null;
        } else {
            $user = Yii::$app->user->getId();
            $quantity = Yii::$app->request->post('quantity');
            if (!$quantity || $quantity < 1) {
                $quantity = 1;
            }
            $cart = Cart::findOne(['user_id' => $user, 'product_id' => $product->id]);
            if ($cart) {
                if ($quantity > $cart->quantity) {
                    $cart->delete();
                } else {
                    $cart->quantity -= $quantity;
                    $cart->save();
                }
                $statusCode = ApiConstant::SC_OK;
                $data = 'Less Product ID: ' . $product->id . ' successfully';
                $message = 'Less Success';
                $error = null;
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $message = 'Less Failed';
                $error = 'The product is not in your cart';
            }
        }
        return ResultHelper::build($statusCode, $data, $message, $error);
    }

    public function actionDelete()
    {
        $product = Product::findOne(['id' => Yii::$app->request->post('product_id')]);
        if (empty($product)) {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = 'Product not found';
            $message = 'Delete Failed';
        } else {
            $user = Yii::$app->user->getId();
            $cart = Cart::findOne(['user_id' => $user, 'product_id' => $product->id]);
            if ($cart) {
                $cart->delete();
                $statusCode = ApiConstant::SC_OK;
                $data = 'Delete Product ID: ' . $product->id . ' successfully';
                $error = null;
                $message = 'Delete Success';
            } else {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = 'The product is not in your cart';
                $message = 'Delete Failed';
            }
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
}