<?php

/**
 * @var \yii\web\View $this
 * @var string $slug
*/

use yii\helpers\Html;

$this->title = 'Toy: ' . Html::encode($slug);
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['toy/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<p>Details for toy with slug: <?= Html::encode($slug) ?></p>
<p>TODO: Here will be toy details later.</p>
