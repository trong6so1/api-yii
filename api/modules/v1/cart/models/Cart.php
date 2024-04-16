<?php

namespace api\modules\v1\cart\models;

use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;
use Yii;
use DateTime;
use DateTimeZone;
class Cart extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%cart}}';
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
        ];
    }
}