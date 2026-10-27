<?php

declare(strict_types=1);

namespace common\services\cart;

use Yii;

class SessionCartStorage implements CartStorageInterface
{
    private const KEY = 'cart';

    public function load(): array
    {
        $items = Yii::$app->session->get(self::KEY, []);
        if (!is_array($items)) {
            return [];
        }

        $clean = [];
        foreach ($items as $productId => $qty) {
            if ((int) $productId > 0 && (int) $qty > 0) {
                $clean[(int) $productId] = (int) $qty;
            }
        }

        return $clean;
    }

    public function save(array $items): void
    {
        Yii::$app->session->set(self::KEY, $items);
    }

    public function clear(): void
    {
        Yii::$app->session->remove(self::KEY);
    }
}
