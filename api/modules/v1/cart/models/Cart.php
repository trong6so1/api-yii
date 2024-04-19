<?php

namespace api\modules\v1\cart\models;

use api\modules\v1\Product\models\Product;
use api\modules\v1\User\models\User;

class Cart extends \common\models\Cart
{
    public static function tableName()
    {
        return '{{%cart}}';
    }

    public function fields()
    {
        return ['quantity', 'product'];
    }

    public function extraFields()
    {
        return ['user_id', 'user'];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}