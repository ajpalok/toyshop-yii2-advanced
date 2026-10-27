<?php

declare(strict_types=1);

namespace frontend\models;

use common\validators\StockValidator;
use Yii;
use yii\base\Model;

class AddToCartForm extends Model
{
    public $product_id;
    public $qty = 1;

    public function rules(): array
    {
        return [
            [['product_id', 'qty'], 'required'],
            ['product_id', 'integer', 'min' => 1],
            ['qty', 'integer', 'min' => 1, 'max' => Yii::$app->params['shop.maxQtyPerItem']],
            ['qty', StockValidator::class],
        ];
    }
}
