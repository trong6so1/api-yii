<?php


namespace api\base;

use api\modules\v1\user\models\User;
use yii\web\IdentityInterface as IdentityInterface;

/**
 * Class Identity
 * @package api\base
 */
class Identity extends User implements IdentityInterface
{

}