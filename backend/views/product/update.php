<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Product $model
 */

$this->title = 'Update Toy: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<h1><?= $this->title ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
