<?php

declare(strict_types=1);

namespace common\validators;

use common\models\Product;
use yii\validators\Validator;

class StockValidator extends Validator
{
    public string $productAttribute = 'product_id';

    public function validateAttribute($model, $attribute): void
    {
        $qty = (int) $model->$attribute;
        $productId = (int) $model->{$this->productAttribute};

        $product = Product::findActive()->andWhere(['id' => $productId])->one();
        if ($product === null) {
            $this->addError($model, $attribute, 'This toy is not available right now.');
            return;
        }

        if ($qty > $product->stock) {
            $this->addError($model, $attribute, 'Only {stock} left in stock.', ['stock' => $product->stock]);
        }
    }
}
