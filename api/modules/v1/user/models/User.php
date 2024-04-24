<?php

namespace api\modules\v1\User\models;

use api\helper\timeBehavior\TimeBehavior;
use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class User extends \common\models\User
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%user}}';
    }

    /**
     * @throws Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            $this->setPasswordHash($this->password);
            $this->generateAuthKey();
            $this->generateAccessToken();
            $this->setIsDeleted();
        } else if (Yii::$app->request->post('password')) {
            $this->setPasswordHash(Yii::$app->request->post('password'));
        }
        return parent::beforeSave($insert);
    }

    public static function find(): ActiveQuery
    {
        return parent::find()->where(['isDeleted' => 0]);
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
}
