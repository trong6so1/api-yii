<?php

namespace api\modules\v1\post\models;

use Yii;

/**
 * @property array|mixed|object|null $title
 * @property array|mixed|object|null $body
 * @property array|mixed|object|null $tags
 */
class Post extends \common\models\Post
{
    public static function tableName(): string
    {
        return '{{%post}}';
    }

    public function beforeSave($insert): string
    {
        if ($insert) {
            $this->setAttribute('created_by', Yii::$app->user->id ?? null);
            $this->setAttribute('reactions', $this->reactions ?? 0);
            $this->setIsDeleted();
        }
        
        if (is_array($this->tags)) {
            $this->tags = implode(',', $this->tags);
        }
        return parent::beforeSave($insert);
    }

}