<?php

class InsertJob extends \yii\base\BaseObject implements \yii\queue\JobInterface
{
    public $modelClass;
    public $data;
    public $fields;

    public function __construct($modelClass, $data, $fields)
    {
        $this->modelClass = $modelClass;
        $this->data = $data;
        $this->fields = $fields;
    }
    public function execute($queue)
    {
        foreach ($this->data as $value) {
            $modelClass = new $this->modelClass;
            foreach ($this->fields as $field) {
                $modelClass[$field] = $value[$field];
            }
            if($modelClass->validate()){
                $modelClass->save();
            }
            else{
                var_dump($modelClass->errors);
            }
        }
        var_dump("run queue successfully");
    }
}