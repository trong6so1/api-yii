<?php

namespace common\models\base;

use common\models\Product;
use yii\db\ActiveRecord;

class baseCart extends ActiveRecord
{
    public function fields(): array
    {
        return ['quantity', 'product'];
    }

    public function extraFields(): array
    {
        return ['user'];
    }

    public function rules(): array
    {
        return [
            ['product_id', 'required'],
            ['product_id', 'exist', 'targetClass' => Product::class, 'targetAttribute' => 'id'],
        ];
    }
}