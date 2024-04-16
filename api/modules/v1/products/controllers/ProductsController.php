<?php

namespace api\modules\v1\products\controllers;

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
class ProductsController extends Controller
{
    public $modelClass = '\api\modules\v1\products\models\Products';
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }

    public function actionDelete($id)
    {
        $product = Products::findOne($id);
        if ($product)
        {
            $product->delete();
            return  ResultHelper::build(ApiConstant::SC_OK,
                'Deleted product ID = ' . $id .' successfully.',
                null,
                'Delete successfully'
            );
        }
        else{
            return ResultHelper::build(
                ApiConstant::SC_BAD_REQUEST,
                null,
                'Delete Failed',
                'Product ID not found'
            );
        }
    }
}