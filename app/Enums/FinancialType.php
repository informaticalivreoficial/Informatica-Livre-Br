<?php

namespace App\Enums;

enum FinancialType: string
{
    case INCOME = 'income';
    case EXPENSE = 'expense';

    public function label(): string
    {
        return match ($this) {
            self::INCOME  => 'Receita',
            self::EXPENSE => 'Despesa',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::INCOME  => 'green',
            self::EXPENSE => 'red',
        };
    }
}