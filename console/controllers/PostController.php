<?php

namespace console\controllers;

use api\modules\v1\User\models\User;
use yii\console\Controller;
use yii\httpclient\Client;

class PostController extends Controller
{
    public function actionAdd()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://fakestoreapi.com/users')
            ->send();
        if ($response->isOk) {
            $modelClass = User::class;
            $fields = ["username", "email", "password"];
            $data = $response->data;
//            Yii::$app->queue->push(new \InsertJob($modelClass, $field, $data));
            foreach ($data as $value) {
                $model = new User();
                $model->username = $value['username'];
                $model->email = $value['email'];
                $model->password = $value['password'];
                if ($model->validate()) {
                    $model->generateAccessToken();
                    $model->generateAuthKey();
                    $model->setPasswordHash($model->password);
                    $db = \Yii::$app->db;
                    $db->createCommand()->insert('user', $model)->execute();
                } else {
                    var_dump(false);
                }
            }
            var_dump("success");
        } else {
            echo "Add Posts Failed";
        }
    }

}