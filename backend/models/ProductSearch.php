<?php

declare(strict_types=1);

namespace backend\models;

use common\models\Product;
use yii\data\ActiveDataProvider;

class ProductSearch extends Product
{
    public function rules(): array
    {
        return [
            [['id', 'category_id', 'status'], 'integer'],
            [['name', 'sku'], 'safe'],
            [['is_featured'], 'boolean'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Product::find()->with('category');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 25],
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'category_id' => $this->category_id,
            'status' => $this->status,
            'is_featured' => $this->is_featured,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'sku', $this->sku]);

        return $dataProvider;
    }
}
