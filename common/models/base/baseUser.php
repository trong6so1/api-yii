<?php

namespace common\models\base;

use yii\db\ActiveRecord;
use yii\db\Query;

class baseUser extends ActiveRecord
{
    public function fields(): array
    {
        return ['id', 'username', 'email', 'created_at', 'updated_at'];
    }

    public function extraFields(): array
    {
        return ['auth_key', 'access_token', 'verification_token', 'isDeleted'];
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username', 'email', 'password'], 'string', 'max' => 255],
            [['username', 'email'], 'unique'],
            ['email', 'email'],
            [['username', 'email'], 'uniqueWithDeleted'],
        ];
    }

    public function uniqueWithDeleted($attribute, $params)
    {
        $query = new Query();
        $exists = $query->from($this->tableName())
            ->where([$attribute => $this->$attribute, 'isDeleted' => 1])
            ->exists();

        if ($exists) {
            $this->addError($attribute, $attribute . " \"" . $this->$attribute . " \"" . ' has already been taken.');
        }
    }
}