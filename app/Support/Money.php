<?php

namespace App\Support;

class Money
{
    public static function parse(?string $value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        $value = str_replace(['R$', ' ', '.'], '', $value);
        $value = str_replace(',', '.', $value);

        return round((float) $value, 2);
    }

    public static function format(float|int|string|null $value): string
    {
        return 'R$ ' . number_format((float) $value, 2, ',', '.');
    }

    public static function cents(float|int|string|null $value): int
    {
        return (int) bcmul(number_format((float) $value, 2, '.', ''), '100', 0);
    }
}
