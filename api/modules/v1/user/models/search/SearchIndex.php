<?php

namespace api\modules\v1\user\models\search;

use api\modules\v1\User\models\User;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class SearchIndex
{
    public static function search($request = null): ActiveDataProvider
    {

        $dataProvider = new ActiveDataProvider([
            'query' => User::find(),
            'pagination' => [
                'pageSize' => $request['perPage'] ?? 10,
            ],
        ]);
        $sort = new Sort([
            'attributes' => [$request['sort'] ?? 'id']
        ]);
        $dataProvider->query->orderBy($sort->orders);
        if (!empty($request['search'])) {
            $dataProvider->query->orFilterWhere(['like', 'username', $request['search']])
                ->orFilterWhere(['like', 'email', $request['search']]);
        }
        return $dataProvider;
    }
}