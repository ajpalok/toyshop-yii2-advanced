<?php

declare(strict_types=1);

namespace common\services\cart;

use common\models\Product;
use common\support\Money;

final class CartLine
{
    public function __construct(
        public readonly Product $product,
        public readonly int $qty,
    ) {
    }

    public function unitPoisha(): int
    {
        return Money::toPoisha($this->product->getEffectivePrice());
    }

    public function linePoisha(): int
    {
        return $this->unitPoisha() * $this->qty;
    }
}
