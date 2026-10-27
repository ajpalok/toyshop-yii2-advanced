<?php

/**
 * @var \yii\web\View $this
 * @var \common\services\cart\CartLine[] $lines
 * @var int $subtotal
 */

use common\support\Money;
use yii\helpers\Html;

$this->title = 'Your Cart';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Your Cart</h1>

<?php if (empty($lines)): ?>
    <p>Your cart is empty. <?= Html::a('Browse toys', ['toy/index']) ?></p>
<?php else: ?>
    <table>
        <thead>
        <tr>
            <th>Toy</th>
            <th>Qty</th>
            <th>Unit price</th>
            <th>Line total</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($lines as $line): ?>
            <tr>
                <td><?= Html::encode($line->product->name) ?></td>
                <td><?= $line->qty ?></td>
                <td><?= Money::fromPoisha($line->unitPoisha()) ?> BDT</td>
                <td><?= Money::fromPoisha($line->linePoisha()) ?> BDT</td>
                <td>
                    <?= Html::beginForm(['cart/remove'], 'post') ?>
                    <?= Html::hiddenInput('product_id', $line->product->id) ?>
                    <?= Html::submitButton('Remove') ?>
                    <?= Html::endForm() ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <p><strong>Subtotal: <?= Money::fromPoisha($subtotal) ?> BDT</strong></p>

    <p><?= Html::a('Proceed to checkout', ['checkout/index']) ?></p>
<?php endif; ?>
