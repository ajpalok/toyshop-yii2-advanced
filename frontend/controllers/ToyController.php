<?php

namespace frontend\controllers;

use yii\web\Controller;

class ToyController extends Controller
{
    public function actionIndex()
    {
        // Logic to display a list of toys
        return $this->render('index');
    }

    public function actionCategory($category)
    {
        // Logic to display toys by category
        return $this->render('category', ['category' => $category]);
    }

    public function actionView($slug)
    {
        // Logic to display a single toy based on its slug
        return $this->render('view', ['slug' => $slug]);
    }
}