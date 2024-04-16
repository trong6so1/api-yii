<?php

namespace api\modules\v1\cart\controllers;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
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
        $user = Yii::$app->user->identity;
        $products = Products::find(['product_id'])->all();
        return $products;
    }

}