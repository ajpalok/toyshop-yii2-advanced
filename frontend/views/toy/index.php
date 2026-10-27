<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Category[] $categories
 * @var \common\models\Product[] $products
 */

use yii\helpers\Html;

$this->title = 'Toys';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1>Toys</h1>

<h2>Categories</h2>
<?php if (empty($categories)): ?>
    <p>No categories yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($categories as $category): ?>
            <li><?= Html::a(Html::encode($category->name), ['toy/category', 'category' => $category->slug]) ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<h2>All toys</h2>
<?php if (empty($products)): ?>
    <p>No toys available yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($products as $product): ?>
            <li>
                <?= Html::a(Html::encode($product->name), ['toy/view', 'slug' => $product->slug]) ?>
                &mdash; <?= Html::encode($product->getEffectivePrice()) ?> BDT
                <?php if (!$product->isInStock()): ?>(out of stock)<?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
