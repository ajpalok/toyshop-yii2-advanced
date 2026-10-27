<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Order $model
 */

use common\models\Order;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Order ' . $model->number;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'number',
        'customer_name',
        'phone',
        'address',
        'city',
        'delivery_zone',
        [
            'attribute' => 'status',
            'value' => Order::statusLabels()[$model->status] ?? $model->status,
        ],
        'payment_method',
        'subtotal',
        'delivery_charge',
        'total',
        'note:ntext',
        'created_at',
        'updated_at',
    ],
]) ?>

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
    <?php foreach ($model->items as $item): ?>
        <tr>
            <td><?= Html::encode($item->product_name) ?></td>
            <td><?= $item->qty ?></td>
            <td><?= Html::encode($item->unit_price) ?></td>
            <td><?= Html::encode($item->line_total) ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<?php if (Yii::$app->user->can('manageOrders')): ?>
    <h2>Change status</h2>
    <?= Html::beginForm(['status', 'id' => $model->id], 'post') ?>
    <?= Html::dropDownList('status', $model->status, Order::statusLabels()) ?>
    <?= Html::submitButton('Update status') ?>
    <?= Html::endForm() ?>
<?php endif; ?>
