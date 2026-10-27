<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Category $model
 */

$this->title = 'Create Category';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
