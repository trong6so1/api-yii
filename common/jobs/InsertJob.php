<?php

namespace common\jobs;

use Yii;

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
        Yii::info("start queue","queue");
        $startTime = microtime(true);

        $sum = count($this->data);
        $total = 0;
        foreach ($this->data as $item) {
            $model = new $this->modelClass;
            if ($model->load($item, '') && $model->validate()) {
                try {
                    $model->save();
                    $total++;
                } catch (\Exception $e) {
                    Yii::error($e->getMessage());
                }
            } else {
                Yii::error($model->getFirstErrors());
            }
        }
        $executionTime = microtime(true) - $startTime;
        Yii::info("Add success " . $total . "/" . $sum, "queue");
        Yii::info("Job executed in $executionTime seconds.", "queue");
    }
}