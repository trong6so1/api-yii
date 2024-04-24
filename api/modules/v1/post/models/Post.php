<?php

namespace api\modules\v1\post\models;

use api\helper\timeBehavior\TimeBehavior;
use Yii;
use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

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

    public function behaviors(): array
    {
        return [
            [
                'class' => TimeBehavior::class,
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
                'replaceRegularDelete' => true
            ],

        ];
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->where(['isDeleted' => 0]);
    }

}