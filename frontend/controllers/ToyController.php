<?php

declare(strict_types=1);

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ToyController extends Controller
{
    public function actionIndex(): string
    {
        return $this->render('index', [
            'categories' => Category::findMenu(),
            'products' => Product::findActive()->orderBy(['name' => SORT_ASC])->all(),
        ]);
    }

    public function actionCategory(string $category): string
    {
        $model = Category::findActive()->andWhere(['slug' => $category])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested category does not exist.');
        }

        $products = Product::findActive()
            ->andWhere(['category_id' => $model->id])
            ->orderBy(['name' => SORT_ASC])
            ->all();

        return $this->render('category', [
            'category' => $model,
            'products' => $products,
        ]);
    }

    public function actionView(string $slug): string
    {
        $model = Product::findActive()->andWhere(['slug' => $slug])->one();

        if ($model === null) {
            throw new NotFoundHttpException('The requested product does not exist.');
        }

        return $this->render('view', ['product' => $model]);
    }
}
