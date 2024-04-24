<?php

namespace common\models;

use api\modules\v1\User\models\User;
use yii\db\ActiveQuery;

/**
 *
 * @property-read mixed $user
 * @property int $isDeleted
 */
class Post extends base\basePost
{
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    public function setIsDeleted()
    {
        $this->isDeleted = 0;
    }
}