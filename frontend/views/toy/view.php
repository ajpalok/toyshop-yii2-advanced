<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Product $product
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $product->name;
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['toy/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($product->name) ?></h1>

<p><?= Html::encode($product->description) ?></p>

<p>
    <?php if ($product->isOnSale()): ?>
        <del><?= Html::encode($product->price) ?></del>
        <strong><?= Html::encode($product->getEffectivePrice()) ?> BDT</strong>
    <?php else: ?>
        <strong><?= Html::encode($product->price) ?> BDT</strong>
    <?php endif; ?>
</p>

<p>SKU: <?= Html::encode($product->sku) ?></p>
<p>Recommended age: <?= Html::encode($product->min_age) ?><?= $product->max_age ? '-' . Html::encode($product->max_age) : '+' ?> years</p>

<?php if ($product->isInStock()): ?>
    <p><?= Html::encode($product->stock) ?> in stock</p>

    <?php $form = ActiveForm::begin(['action' => ['cart/add'], 'method' => 'post']) ?>
    <?= Html::hiddenInput('product_id', $product->id) ?>
    <?= Html::label('Quantity', 'qty') ?>
    <?= Html::input('number', 'qty', 1, ['id' => 'qty', 'min' => 1, 'max' => Yii::$app->params['shop.maxQtyPerItem']]) ?>
    <?= Html::submitButton('Add to cart') ?>
    <?php ActiveForm::end() ?>
<?php else: ?>
    <p>Out of stock.</p>
<?php endif; ?>
