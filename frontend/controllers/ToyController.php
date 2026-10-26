<?php

namespace frontend\controllers;

use common\models\Category;
use common\models\Product;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ToyController extends Controller
{
    public function actionIndex()
    {
        // Logic to display a list of toys
        return $this->render('index');
    }

    public function actionCategory($category)
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

    public function actionView($slug)
    {
        // Logic to display a single toy based on its slug
        return $this->render('view', ['slug' => $slug]);
    }
}