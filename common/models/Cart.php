<?php

namespace common\models;

use api\modules\v1\product\models\Product;
use api\modules\v1\User\models\User;
use common\models\base\baseCart;
use yii\db\ActiveQuery;

/**
 *
 * @property-read ActiveQuery $product
 * @property-read ActiveQuery $user
 */
class Cart extends baseCart
{
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function getProduct(): ActiveQuery
    {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }
}