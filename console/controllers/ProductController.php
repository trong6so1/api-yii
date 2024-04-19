<?php

namespace console\controllers;

use common\jobs\InsertJob;
use api\modules\v1\product\models\Product;
use yii\console\Controller;
use yii\httpclient\Client;
use Yii;

class ProductController extends Controller
{
    public function actionAdd()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('GET')
            ->setUrl('https://fakestoreapi.com/products')
            ->send();
        if ($response->isOk) {
            $data = $response->data;
            $modelClass = Product::class;
            Yii::$app->queue->push(new InsertJob($modelClass, $data));
            echo "Get HTTP Client Success.Run queue Insert";
        } else {
            echo "Get HTTP Client Failed";
        }
    }
}