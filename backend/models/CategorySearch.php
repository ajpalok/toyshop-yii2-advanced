<?php

declare(strict_types=1);

namespace backend\models;

use common\models\Category;
use yii\data\ActiveDataProvider;

class CategorySearch extends Category
{
    public function rules(): array
    {
        return [
            [['id', 'sort_order'], 'integer'],
            [['name', 'slug'], 'safe'],
            [['is_active'], 'boolean'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Category::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 25],
            'sort' => ['defaultOrder' => ['sort_order' => SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'is_active' => $this->is_active,
        ]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'slug', $this->slug]);

        return $dataProvider;
    }
}
