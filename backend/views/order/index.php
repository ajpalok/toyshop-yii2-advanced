<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\models\Order;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Orders</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'number',
            'format' => 'raw',
            'value' => static fn (Order $order) => Html::a(Html::encode($order->number), ['view', 'id' => $order->id]),
        ],
        'customer_name',
        [
            'attribute' => 'status',
            'value' => static fn (Order $order) => Order::statusLabels()[$order->status] ?? $order->status,
        ],
        'total',
        'created_at',
        ['class' => ActionColumn::class, 'template' => '{view}'],
    ],
]) ?>
