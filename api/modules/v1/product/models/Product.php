<?php

namespace api\modules\v1\product\models;

use api\modules\v1\user\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;

class Product extends ActiveRecord
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
            [['title', 'price', 'discountPercentage', 'rating', 'brand', 'category'], 'required'],
            [['description'], 'string'],
            [['price', 'discountPercentage', 'rating'], 'number'],
            [['stock'], 'integer'],
            [['title', 'brand', 'category'], 'string', 'max' => 255],
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('created_by', Yii::$app->user->id);
        }
        return parent::beforeSave($insert);
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s');
                },
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ],

        ];
    }

    public static function find()
    {
        return parent::find()->where(['isDeleted' => null]);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

}