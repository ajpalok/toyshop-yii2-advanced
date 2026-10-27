<?php

/**
 * @var \yii\web\View $this
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use common\models\Order;
use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Your Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Your Orders</h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'attribute' => 'number',
            'format' => 'raw',
            'value' => static fn (Order $order) => Html::a(
                Html::encode($order->number),
                ['order/view', 'number' => $order->number],
            ),
        ],
        [
            'attribute' => 'status',
            'value' => static fn (Order $order) => Order::statusLabels()[$order->status] ?? $order->status,
        ],
        'total',
        'created_at',
    ],
]) ?>
