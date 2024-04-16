<?php


namespace api\base;

use api\modules\v1\users\models\Users;
use yii\web\IdentityInterface as IdentityInterface;

/**
 * Class Identity
 * @package api\base
 */
class Identity extends Users implements IdentityInterface
{

}