<?php

namespace api\modules\v1\product\models;

use Yii;

/**
 * @property mixed|null $id
 * @property array|mixed|object|null $title
 * @property array|mixed|object|null $description
 * @property array|mixed|object|null $price
 * @property array|mixed|object|null $stock
 * @property array|mixed|object|null $category
 */
class Product extends \common\models\Product
{
    public static function tableName(): string
    {
        return '{{%product}}';
    }

    public function beforeSave($insert): bool
    {
        if ($insert) {
            $this->setAttribute('created_by', Yii::$app->user->id ?? null);
            $this->setIsDeleted();
        }
        return parent::beforeSave($insert);
    }
}