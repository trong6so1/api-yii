<?php

namespace common\models\base;

use yii\db\ActiveRecord;

class basePost extends ActiveRecord
{
    public function rules(): array
    {
        return [
            [['title'], 'required'],
            [['body'], 'string'],
            [['created_by', 'reactions'], 'integer'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function fields(): array
    {
        return ['id', 'title', 'body', 'tags', 'reactions', 'user', 'created_at', 'updated_at',];
    }

    public function extraFields(): array
    {
        return ['isDeleted'];
    }
}