<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Category $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($model->name) ?></h1>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->id]) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'data' => [
            'confirm' => 'Are you sure you want to delete this category?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'name',
        'slug',
        'sort_order',
        'is_active:boolean',
    ],
]) ?>
