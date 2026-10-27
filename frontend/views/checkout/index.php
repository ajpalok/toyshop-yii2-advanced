<?php

/**
 * @var \yii\web\View $this
 * @var \frontend\models\CheckoutForm $form
 * @var \common\services\cart\CartLine[] $lines
 * @var int $subtotal
 */

use common\support\Money;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Checkout';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Checkout</h1>

<h2>Order summary</h2>
<ul>
    <?php foreach ($lines as $line): ?>
        <li>
            <?= Html::encode($line->product->name) ?> &times; <?= $line->qty ?>
            &mdash; <?= Money::fromPoisha($line->linePoisha()) ?> BDT
        </li>
    <?php endforeach; ?>
</ul>
<p>Subtotal: <?= Money::fromPoisha($subtotal) ?> BDT</p>

<h2>Delivery details</h2>
<?php $activeForm = ActiveForm::begin(['action' => ['checkout/place']]) ?>

<?= $activeForm->field($form, 'customer_name') ?>
<?= $activeForm->field($form, 'phone') ?>
<?= $activeForm->field($form, 'address')->textarea() ?>
<?= $activeForm->field($form, 'city') ?>
<?= $activeForm->field($form, 'delivery_zone')->dropDownList(
    array_map(
        static fn (int $charge) => Html::encode($charge) . ' BDT delivery',
        Yii::$app->params['shop.deliveryCharge'],
    ),
) ?>
<?= $activeForm->field($form, 'note')->textarea() ?>

<?= Html::submitButton('Place order') ?>

<?php ActiveForm::end() ?>
