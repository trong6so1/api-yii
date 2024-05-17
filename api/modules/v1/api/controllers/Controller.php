<?php

namespace api\modules\v1\api\controllers;


use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;

class Controller extends \yii\rest\Controller
{
    public function verbs(): array
    {
        return [
            'create' => ['GET']
        ];
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => [
                HttpBearerAuth::class,
            ],
            'except' => ['login', 'signup', 'logout']
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'except' => ['login', 'signup'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['admin'],
                ],
            ],
        ];
        return $behaviors;
    }
}