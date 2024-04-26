<?php

namespace console\controllers;

use common\jobs\InsertUserJob;
use Yii;
use api\modules\v1\User\models\User;
use common\jobs\InsertJob;
use yii\console\Controller;
use yii\httpclient\Client;

class UserController extends Controller
{
    public function actionAdd()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://fakestoreapi.com/users')
            ->send();
        if ($response->isOk) {
            $data = $response->data;
            Yii::$app->queue->push(new InsertUserJob(['data' => $data]));
            echo "Get HTTP Client Success.Run queue Insert";
        } else {
            echo "Get HTTP Client Failed";
        }
    }
}