<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Product $model
 */

$this->title = 'Create Toy';
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= $this->title ?></h1>

<?= $this->render('_form', ['model' => $model]) ?>
