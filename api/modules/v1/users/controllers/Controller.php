<?php

namespace api\modules\v1\users\controllers;


use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;

/**
 * Class Controller
 * @package api\modules\v1\article\controllers
 */
class Controller extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
            'except' => ['login','logout'],
        ];
        return $behaviors;
    }
}