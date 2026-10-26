<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Category $category
 * @var \common\models\Product[] $products
 */

use yii\helpers\Html;

$this->title = $category->name;
$this->params['breadcrumbs'][] = ['label' => 'Toys', 'url' => ['toy/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($category->name) ?></h1>

<?php if (empty($products)): ?>
    <p>No toys found in this category yet.</p>
<?php else: ?>
    <div class="toy-list">
        <?php foreach ($products as $product): ?>
            <div class="toy-list-item">
                <h3>
                    <?= Html::a(Html::encode($product->name), ['toy/view', 'slug' => $product->slug]) ?>
                </h3>
                <p>
                    <?php if ($product->isOnSale()): ?>
                        <del><?= Html::encode($product->price) ?></del>
                        <strong><?= Html::encode($product->getEffectivePrice()) ?></strong>
                    <?php else: ?>
                        <strong><?= Html::encode($product->price) ?></strong>
                    <?php endif; ?>
                </p>
                <?php if (!$product->isInStock()): ?>
                    <p>Out of stock</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
