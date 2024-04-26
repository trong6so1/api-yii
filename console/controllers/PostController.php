<?php

namespace console\controllers;

use api\modules\v1\post\models\Post;
use common\jobs\InsertPostJob;
use Yii;
use common\jobs\InsertJob;
use yii\console\Controller;
use yii\httpclient\Client;

class PostController extends Controller
{
    public function actionAdd()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://jsonplaceholder.typicode.com/posts')
            ->send();
        if ($response->isOk) {
            $data = $response->data;
            $modelClass = Post::class;
            Yii::$app->queue->push(new InsertPostJob(['data' => $data]));
            echo "Get HTTP Client Success.Run queue Insert";
        } else {
            echo "Get HTTP Client Failed";
        }
    }

}