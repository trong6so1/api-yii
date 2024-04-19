<?php

namespace common\models;

use api\helper\response\ApiConstant;
use api\helper\response\ResultHelper;
use yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii2tech\ar\softdelete\SoftDeleteBehavior;

class
Base extends ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => function () {
                    return Yii::$app->formatter->asDatetime(time(), 'php:Y-m-d H:i:s');
                },
            ],
            'softDeleteBehavior' => [
                'class' => SoftDeleteBehavior::className(),
                'softDeleteAttributeValues' => [
                    'isDeleted' => true
                ],
                'replaceRegularDelete' => true // mutate native `delete()` method
            ],

        ];
    }

    public static function find()
    {
        return parent::find()->where(['isDeleted' => null]);
    }

    public function actionCreate()
    {
        if ($this->load(Yii::$app->request->post(), '') && $this->validate()) {
            try {
                $this->insert();
                $statusCode = ApiConstant::SC_OK;
                $data = $this;
                $error = null;
                $message = 'Create ' . $this->tableSchema->name . ' successfully';
            } catch (\Exception $e) {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = $e->getMessage();
                $message = 'An error occurred while creating ' . $this->tableSchema->name;
            }

        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $this->getFirstErrors();
            $message = 'An error occurred while creating ' . $this->tableSchema->name;
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionUpdate()
    {
        if ($this->load(Yii::$app->request->post(), '') && $this->validate()) {
            try {
                $statusCode = ApiConstant::SC_OK;
                $data = $this;
                $error = null;
                $message = 'Update ' . $this->tableSchema->name . ' successfully';
            } catch (\Exception $e) {
                $statusCode = ApiConstant::SC_BAD_REQUEST;
                $data = null;
                $error = $e->getMessage();
                $message = 'An error occurred while updating ' . $this->tableSchema->name;
            }
        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $this->getErrors();
            $message = 'An error occurred while updating ' . $this->tableSchema->name;
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }

    public function actionDelete()
    {
        $this->delete();
        if ($this->isDeleted == 1) {
            $statusCode = ApiConstant::SC_OK;
            $data = [
                'id' => $this->id
            ];
            $error = null;
            $message = 'Delete ' . $this->tableSchema->name . ' successfully';

        } else {
            $statusCode = ApiConstant::SC_BAD_REQUEST;
            $data = null;
            $error = $this->getFirstErrors();
            $message = 'An error occurred while Deleting ' . $this->tableSchema->name;
        }
        return ResultHelper::build($statusCode, $data, $error, $message);
    }
}