<?php

namespace api\modules\v1\api\controllers;

use Yii;

class OrderTipController extends Controller
{
    public function actionCreate(): array
    {
        $url = 'v1/order/order-tip/create';
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