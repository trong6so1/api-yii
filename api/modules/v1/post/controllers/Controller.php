<?php

namespace api\modules\v1\post\controllers;


use api\modules\v1\post\models\Post;
use Yii;
use yii\filters\AccessControl;
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
        return [
            'index' => ['GET'],
            'view' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT', 'POST'],
            'delete' => ['DELETE', 'GET'],
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
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['author', 'admin'],
                ],
                [
                    'allow' => true,
                    'actions' => ['create'],
                    'roles' => ['create'],
                ],
                [
                    'allow' => true,
                    'actions' => ['update', 'delete'],
                    'roles' => ['update'],
                    'roleParams' => function () {
                        return ['post' => Post::findOne(['id' => Yii::$app->request->get('id')])];
                    },
                ],
            ],
        ];
        return $behaviors;
    }
}