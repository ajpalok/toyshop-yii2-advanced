<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Order $order
 */

use common\models\Order;
use yii\helpers\Html;

$this->title = 'Order ' . $order->number;
$this->params['breadcrumbs'][] = ['label' => 'Your Orders', 'url' => ['order/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Order <?= Html::encode($order->number) ?></h1>

<p>Status: <?= Html::encode(Order::statusLabels()[$order->status] ?? $order->status) ?></p>
<p>Placed on: <?= Html::encode($order->created_at) ?></p>

<h2>Delivery details</h2>
<p>
    <?= Html::encode($order->customer_name) ?><br>
    <?= Html::encode($order->phone) ?><br>
    <?= Html::encode($order->address) ?>, <?= Html::encode($order->city) ?>
</p>
<?php if ($order->note): ?>
    <p>Note: <?= Html::encode($order->note) ?></p>
<?php endif; ?>

<h2>Items</h2>
<table>
    <thead>
    <tr>
        <th>Toy</th>
        <th>Qty</th>
        <th>Unit price</th>
        <th>Line total</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($order->items as $item): ?>
        <tr>
            <td><?= Html::encode($item->product_name) ?></td>
            <td><?= $item->qty ?></td>
            <td><?= Html::encode($item->unit_price) ?> BDT</td>
            <td><?= Html::encode($item->line_total) ?> BDT</td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<p>
    Subtotal: <?= Html::encode($order->subtotal) ?> BDT<br>
    Delivery: <?= Html::encode($order->delivery_charge) ?> BDT<br>
    <strong>Total: <?= Html::encode($order->total) ?> BDT</strong>
</p>

<?php if ($order->canMoveTo(Order::STATUS_CANCELLED)): ?>
    <?= Html::beginForm(['order/cancel', 'number' => $order->number], 'post') ?>
    <?= Html::submitButton('Cancel order') ?>
    <?= Html::endForm() ?>
<?php endif; ?>
