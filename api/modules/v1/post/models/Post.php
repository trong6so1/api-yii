<?php

namespace api\modules\v1\post\models;

use api\modules\v1\User\models\User;
use common\models\Base;
use Yii;

class Post extends Base
{
    public static function tableName()
    {
        return '{{%post}}';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
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
            $this->setAttribute('created_by', Yii::$app->user->id ?? null);
        }
        return parent::beforeSave($insert);
    }

}