<?php

namespace api\modules\v1\api\controllers;

use api\helper\response\ResultHelper;
use yii\httpclient\Client;
use Yii;
use yii\httpclient\Exception;

class OrderPaymentMethodController extends Controller
{
    /**
     * @throws Exception
     */
    public function actionCreate(): array
    {
        $request = Yii::$app->request->queryParams;
        $client = new Client([
            'baseUrl' => Yii::$app->report->hostInfo,
        ]);
        $header = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => $request['token'] ?? "",
        ];
        $response = $client->post('v1/order/order-payment-method/create',
            $request,
            $header
        )->send();
        if ($response->isOk) {
            return $response->data;
        } else {
            $response = json_decode($response->content);
            $statusCode = $response->status;
            $data = null;
            $errol = $response->message;
            $message = $response->name;
        }
        return ResultHelper::build($statusCode, $data, $errol, $message);
    }
}