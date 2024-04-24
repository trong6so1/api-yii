<?php

namespace api\modules\v1\cart\models;

use Yii;

/**
 * @property array|int|mixed|object|null $quantity
 * @property mixed|null $product_id
 * @property int|mixed|string|null $user_id
 */
class Cart extends \common\models\Cart
{
    public static function tableName(): string
    {
        return '{{%cart}}';
    }

    public function beforeSave($insert): bool
    {
        if ($insert) {
            $this->user_id = Yii::$app->user->id;
        }
        return parent::beforeSave($insert);
    }
}