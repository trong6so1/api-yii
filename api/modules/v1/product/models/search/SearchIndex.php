<?php

namespace api\modules\v1\product\models\search;

use api\modules\v1\product\models\Product;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class SearchIndex
{
    public static function search($request = null): ActiveDataProvider
    {

        $dataProvider = new ActiveDataProvider([
            'query' => Product::find(),
            'pagination' => [
                'pageSize' => $request['perPage'] ?? 10,
            ],
        ]);
        $sort = new Sort([
            'attributes' => [$request['sort'] ?? 'id']
        ]);
        $dataProvider->query->orderBy($sort->orders);
        if (!empty($request['search'])) {
            $dataProvider->query->orFilterWhere(['like', 'title', $request['search']])
                ->orFilterWhere(['like', 'description', $request['search']]);
        }
        return $dataProvider;
    }
}