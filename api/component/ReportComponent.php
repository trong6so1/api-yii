<?php

namespace api\component;

use api\helper\response\ResultHelper;
use yii\base\Component;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class ReportComponent extends Component
{
    public $hostInfo;

    /**
     * @throws Exception
     */
    public function callApi($method,$url, $config)
    {
        $client = new Client([
            'baseUrl' => $this->hostInfo,
        ]);
        $params = $config['params'] ?? null;
        $header = $config['header'] ?? [];
        $response = $client->$method($url,
            $params,
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