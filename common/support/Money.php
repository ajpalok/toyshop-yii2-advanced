<?php

declare(strict_types=1);

namespace common\support;

/**
 * Converts between decimal taka strings and integer poisha, so cart/checkout math
 * never touches floats (float addition loses cents, e.g. 0.1 + 0.2 !== 0.3).
 */
final class Money
{
    public static function toPoisha(string|int|float $amount): int
    {
        return (int) round((float) $amount * 100);
    }

    public static function fromPoisha(int $poisha): string
    {
        return number_format($poisha / 100, 2, '.', '');
    }
}
