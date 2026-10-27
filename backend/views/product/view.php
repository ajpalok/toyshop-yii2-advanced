<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Product $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($model->name) ?></h1>

<p>
    <?= Html::a('Update', ['update', 'id' => $model->id]) ?>
    <?= Html::a('Delete', ['delete', 'id' => $model->id], [
        'data' => [
            'confirm' => 'Are you sure you want to delete this toy?',
            'method' => 'post',
        ],
    ]) ?>
</p>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'category.name',
        'name',
        'slug',
        'sku',
        'description:ntext',
        'price',
        'sale_price',
        'stock',
        'min_age',
        'max_age',
        'is_featured:boolean',
        [
            'attribute' => 'status',
            'value' => $model->status === \common\models\Product::STATUS_ACTIVE ? 'Active' : 'Draft',
        ],
    ],
]) ?>
