<?php

namespace api\modules\v1\cart\models;

use dmstr\db\tests\unit\Product;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use DateTime;
use DateTimeZone;
class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cart}}';
    }
    public function fields()
    {
        return ['product_id','user_id','quantity'];
    }

    public function getUser()
    {
        return $this->hasOne('api\modules\v1\models\User', ['id' => 'user_id']);
    }
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}