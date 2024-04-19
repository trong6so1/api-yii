<?php

namespace api\modules\v1\product\models;

use api\modules\v1\user\models\User;
use common\models\Base;
use Yii;

class Product extends Base
{
    public static function tableName()
    {
        return '{{%product}}';
    }

    public function fields()
    {
        return ['id', 'title', 'description', 'price', 'discountPercentage', 'rating', 'stock',
            'category', 'brand', 'user', 'created_at', 'updated_at'];
    }

    public function extraFields()
    {
        return ['isDeleted'];
    }

    public function rules()
    {
        return [
            [['title', 'price', 'category'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['stock'], 'integer'],
            [['title', 'category'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('created_by', $this->created_by ?? Yii::$app->user->id);
        }
        return parent::beforeSave($insert);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

}