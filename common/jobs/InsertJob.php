<?php

namespace common\jobs;

use api\modules\v1\product\models\Product;

class InsertJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;
    public $data;

    public function __construct($modelClass, $data)
    {
        $this->modelClass = $modelClass;
        $this->data = $data;
    }

    public function execute($queue)
    {
        $sum = count($this->data);
        $total = 0;
        foreach ($this->data as $item) {
            $model = new $this->modelClass;
            if ($model->load($item, '') && $model->validate()) {
                try {
                    $model->insert();
                    $total++;
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }
            } else {
                var_dump($model->getFirstErrors());
            }
        }
        echo "Add success " . $total . "/" . $sum;
    }
}