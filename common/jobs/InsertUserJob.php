<?php

namespace common\jobs;

use api\modules\v1\User\models\User;
use yii\base\BaseObject;
use yii\db\Exception;
use yii\queue\JobInterface;
use Yii;

class InsertUserJob extends BaseObject implements JobInterface
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
        Yii::info("start queue", 'queue');
        $sum = count($this->data);
        $total = 0;
        foreach ($this->data as $item) {
            $model = new User();
            if ($model->load($item, '') && $model->validate()) {
                if ($model->save()) {
                    $total++;
                    $mailer = Yii::$app->mailer;
                    $mailer->compose()
                        ->setFrom('trong6so1@gmail.com')
                        ->setTo($model->email)
                        ->setSubject('create user')
                        ->setTextBody('Successful user initialization')
                        ->setHtmlBody('token'.$model->getAccessToken())
                        ->send();
                } else {
                    Yii::error('There was an error while adding the user', 'queue');
                }
            } else {
                Yii::error($model->getFirstErrors());
            }
        }
        Yii::info("Add success " . $total . "/" . $sum, 'queue');
    }
}