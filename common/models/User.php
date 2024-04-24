<?php

namespace common\models;

use common\models\base\baseUser;
use Yii;
use yii\base\Exception;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property-write mixed $passwordHash
 * @property-read null|string $accessToken
 * @property-read null|string $authKey
 * @property string $password write-only password
 * @property int $isDeleted
 */
class User extends baseUser implements IdentityInterface
{
    /**
     * @var string
     */
    private $access_token;

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public static function findByUsername($username): ?User
    {
        return static::findOne(['username' => $username]);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey(): ?string
    {
        return $this->auth_key;
    }

    public function getAccessToken(): ?string
    {
        return $this['access_token'];
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * @throws Exception
     */
    public function setPasswordHash($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function setIsDeleted()
    {
        $this->isDeleted = 0;
    }

    /**
     * @throws Exception
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @throws Exception
     */
    public function generateAccessToken()
    {
        $this->access_token = Yii::$app->security->generateRandomString();
    }
}
