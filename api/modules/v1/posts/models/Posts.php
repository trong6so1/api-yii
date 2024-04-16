<?php

namespace api\modules\v1\posts\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use Yii;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use DateTime;
use DateTimeZone;

class Posts extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%posts}}';
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
    public function getUser()
    {
        return $this->hasOne('api\modules\v1\users\models\User', ['id' => 'created_by']);
    }

    public function getTags()
    {
        return explode(',', $this->tags);

    }


    public function beforeSave($insert)
    {
        if (is_array($this->tags)) {
            $this->setAttribute('tags', implode(',',$this->tags));
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
                    $dateTime = new DateTime('now', new DateTimeZone('Asia/Ho_Chi_Minh'));
                    return $dateTime->format('Y-m-d H:i:s');
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