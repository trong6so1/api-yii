<?php

namespace common\models;

use api\modules\v1\User\models\User;
use common\models\base\baseProduct;
use yii\db\ActiveQuery;

/**
 *
 * @property-read mixed $user
 * @property int $isDeleted
 */
class Product extends baseProduct
{
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function setIsDeleted()
    {
        $this->isDeleted = 0;
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->andWhere(['isDeleted' => 0]);
    }
}