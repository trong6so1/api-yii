<?php

namespace common\models\base;

use yii\db\ActiveRecord;

class baseProduct extends ActiveRecord
{
    public function fields(): array
    {
        return ['id', 'title', 'description', 'price', 'stock',
            'category', 'user', 'created_at', 'updated_at'];
    }

    public function extraFields(): array
    {
        return ['isDeleted'];
    }

    public function rules(): array
    {
        return [
            [['title', 'price', 'category'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['stock'], 'integer'],
            [['title', 'category'], 'string', 'max' => 255],
        ];
    }
}