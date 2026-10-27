<?php

declare(strict_types=1);

namespace common\services\cart;

interface CartStorageInterface
{
    /**
     * @return array<int, int> product_id => qty
     */
    public function load(): array;

    /**
     * @param array<int, int> $items product_id => qty
     */
    public function save(array $items): void;

    public function clear(): void;
}
