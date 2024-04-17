<?php

namespace api\modules\v1\post\models;

use api\modules\v1\User\models\User;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class Post extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%post}}';
    }

    public function rules()
    {
        return [
            [['title', 'tags', 'reactions'], 'required'],
            [['body'], 'string'],
            [['created_by', 'reactions'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function fields()
    {
        return ['id', 'title', 'body', 'tags', 'reactions', 'user', 'created_at', 'updated_at',];
    }

    public function extraFields()
    {
        return ['isDeleted'];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function getTags()
    {
        return explode(',', $this->tags);

    }


    public function beforeSave($insert)
    {
        if (is_array($this->tags)) {
            $this->setAttribute('tags', implode(',', $this->tags));
        }
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

}