<?php

namespace api\modules\v1\cart\controllers;


use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class Controller
 * @package api\modules\v1\cart\controllers
 */
class Controller extends \yii\rest\Controller
{

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
        ];
        return $behaviors;
    }
}