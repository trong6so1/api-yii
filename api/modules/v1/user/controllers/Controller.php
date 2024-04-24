<?php

namespace api\modules\v1\user\controllers;

use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class Controller
 * @package api\modules\v1\article\controllers
 */
class Controller extends \yii\rest\Controller
{
    public function verbs(): array
    {
        $verbs = [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT','POST'],
            'delete' => ['DELETE','GET'],
            'login' => ['POST'],
            'signup' => ['POST'],
            'logout' => ['GET'],
        ];
        return array_merge(parent::verbs(),$verbs);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
            'except' => ['login', 'signup','logout']
        ];
        return $behaviors;
    }
}