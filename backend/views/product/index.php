<?php

/**
 * @var \yii\web\View $this
 * @var \backend\models\ProductSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\models\Product;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Toys';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Toys</h1>

<p><?= Html::a('Create Toy', ['create']) ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'sku',
        'category.name',
        'price',
        'stock',
        [
            'attribute' => 'status',
            'value' => static fn (Product $model) => $model->status === Product::STATUS_ACTIVE ? 'Active' : 'Draft',
        ],
        ['class' => ActionColumn::class],
    ],
]) ?>
