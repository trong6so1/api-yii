<?php

namespace api\modules\v1\cart\models\search;

use api\modules\v1\cart\models\Cart;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Sort;

class SearchIndex
{
    public static function search($request = null): ActiveDataProvider
    {
        $user = Yii::$app->user->getId();
        $dataProvider = new ActiveDataProvider([
            'query' => Cart::find()->andWhere(['user_id' => $user]),
            'pagination' => [
                'pageSize' => $request['perPage'] ?? 10,
            ],
        ]);
        $sort = new Sort([
            'attributes' => [$request['sort'] ?? 'id']
        ]);
        $dataProvider->query->orderBy($sort->orders);
        if (!empty($request['product_id'])) {
            $dataProvider->query->andFilterWhere(['product_id' => $request['product_id']]);
        }
        return $dataProvider;
    }
}