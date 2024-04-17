<?php

namespace api\modules\v1\cart\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use api\modules\v1\cart\models\Cart;
use api\modules\v1\posts\models\Posts;
use api\modules\v1\products\models\Products;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use DateTime;
use DateTimeZone;
class CartController extends Controller
{
    public $modelClass = '\api\modules\v1\cart\models\Products';
    public function actions()
    {
        $action = parent::actions();
        unset($action['index'], $action['update'], $action['delete'], $action['view']);
        return $action;
    }

    public function actionIndex(){
        $user = Yii::$app->user->getId();
        $cart = Cart::findAll(['user_id'=>$user]);
        return $cart;
    }
    public function actionAdd()
    {
        $product = Products::findOne(['id'=>Yii::$app->request->post('product_id')]);
        if(empty($product)){
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Product not found',
                'Add Failed'
            );
        }
        $user= Yii::$app->user->getId();
        $quantity = Yii::$app->request->post('quantity');
        if(!$quantity || $quantity < 1){
            $quantity = 1;
        }
        $cart = Cart::findOne(['user_id'=>$user,'product_id'=>$product->id]);
        if($cart)
        {
            $cart->quantity += $quantity;
            $cart->save();
        }
        else {
            $cart = new Cart();
            $cart->product_id = $product->id;
            $cart->quantity = $quantity;
            $cart->user_id = $user;
            $cart->save();
        }
        return ResultHelper::build(
            ApiConstant::SC_OK,
            'Add Product ID: '.$product->id.' successfully',
            null,
            'Add Success'
        );
    }
    public function actionLess()
    {
        $product = Products::findOne(['id'=>Yii::$app->request->post('product_id')]);
        if(empty($product)){
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Product not found',
                'Less Failed'
            );
        }
        $user= Yii::$app->user->getId();
        $quantity = Yii::$app->request->post('quantity');
        if(!$quantity || $quantity < 1){
            $quantity = 1;
        }
        $cart = Cart::findOne(['user_id'=>$user,'product_id'=>$product->id]);
        if($cart)
        {
            if($quantity > $cart->quantity)
            {
                $cart->delete();
            }
            else{
                $cart->quantity -= $quantity;
                $cart->save();
            }
            return ResultHelper::build(
                ApiConstant::SC_OK,
                'Less Product ID: '.$product->id.' successfully',
                null,
                'Less Success'
            );
        }
        else {
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'The product is not in your cart',
                'Less Failed'
            );
        }
    }
    public function actionDelete()
    {
        $product = Products::findOne(['id'=>Yii::$app->request->post('product_id')]);
        if(empty($product)){
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Product not found',
                'Delete Failed'
            );
        }
        $user= Yii::$app->user->getId();
        $cart = Cart::findOne(['user_id'=>$user,'product_id'=>$product->id]);
        if($cart)
        {
            $cart->delete();
            return ResultHelper::build(
                ApiConstant::SC_OK,
                'Delete Product ID: '.$product->id.' successfully',
                null,
                'Delete Success'
            );
        }
        else
        {
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'The product is not in your cart',
            'Delete Failed'
            );
        }
    }
}