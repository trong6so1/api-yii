<?php

namespace common\jobs;

use common\models\Product;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\queue\JobInterface;
use Yii;

class InsertProductJob extends BaseObject implements JobInterface
{
    public $data;

    public function __construct(array $config = [])
    {
        $this->data = $config['data'];
        parent::__construct($config);
    }

    /**
     * @throws Exception
     */
    public function execute($queue)
    {
        Yii::info("start queue");
        $sum = count($this->data);
        $total = 0;
        foreach ($this->data as $item) {
            $model = new Product();
            if ($model->load($item, '') && $model->validate()) {
                if ($model->save()) {
                    $total++;
                } else {
                    Yii::error('There was an error while adding the user');
                }
            } else {
                Yii::error($model->getFirstErrors());
            }
        }
        Yii::info("Add success " . $total . "/" . $sum);
    }
}