<?php

/**
 * @var \yii\web\View $this
 * @var \backend\models\CategorySearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Categories</h1>

<p><?= Html::a('Create Category', ['create']) ?></p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'name',
        'slug',
        'sort_order',
        'is_active:boolean',
        ['class' => ActionColumn::class],
    ],
]) ?>
