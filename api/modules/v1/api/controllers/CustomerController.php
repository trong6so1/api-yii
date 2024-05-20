<?php

namespace api\modules\v1\api\controllers;

use Yii;

class CustomerController extends Controller
{
    public function actionCreate(): array
    {
        $url = 'v1/category/site/create';
        $method = 'POST';
        $request = Yii::$app->request->queryParams;
        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $request['token'] ?? "",
        ];
        $config = [
            'header' => $header,
            'params' => $request,
        ];
        return Yii::$app->report->callApi($method, $url, $config);
    }
}