<?php

declare(strict_types=1);

namespace common\services\cart;

use common\models\Product;
use Yii;

class CartService
{
    public function __construct(private CartStorageInterface $storage)
    {
    }

    public function add(int $productId, int $qty): void
    {
        $items = $this->storage->load();
        $items[$productId] = min(($items[$productId] ?? 0) + $qty, $this->maxQty());
        $this->storage->save($items);
    }

    public function update(int $productId, int $qty): void
    {
        $items = $this->storage->load();
        if ($qty <= 0) {
            unset($items[$productId]);
        } else {
            $items[$productId] = min($qty, $this->maxQty());
        }
        $this->storage->save($items);
    }

    public function remove(int $productId): void
    {
        $this->update($productId, 0);
    }

    public function clear(): void
    {
        $this->storage->clear();
    }

    /**
     * @return array<int, int>
     */
    public function getItems(): array
    {
        return $this->storage->load();
    }

    public function getCount(): int
    {
        return array_sum($this->storage->load());
    }

    /**
     * @return CartLine[]
     */
    public function getLines(): array
    {
        $items = $this->storage->load();
        if ($items === []) {
            return [];
        }

        $products = Product::findActive()
            ->andWhere(['id' => array_keys($items)])
            ->indexBy('id')
            ->all();

        $lines = [];
        foreach ($items as $productId => $qty) {
            if (isset($products[$productId])) {
                $lines[] = new CartLine($products[$productId], $qty);
            }
        }

        return $lines;
    }

    public function getSubtotalPoisha(): int
    {
        return array_sum(array_map(fn (CartLine $l) => $l->linePoisha(), $this->getLines()));
    }

    private function maxQty(): int
    {
        return (int) Yii::$app->params['shop.maxQtyPerItem'];
    }
}
