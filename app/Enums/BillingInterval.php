<?php

namespace App\Enums;

enum BillingInterval: string {
    case Monthly     = 'monthly';
    case Quarterly   = 'quarterly';
    case Semiannual  = 'semiannual';
    case Yearly      = 'yearly';

    public function months(): int
    {
        return match ($this) {
            self::Monthly    => 1,
            self::Quarterly  => 3,
            self::Semiannual => 6,
            self::Yearly     => 12,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::Monthly    => 'Mensal',
            self::Quarterly  => 'Trimestral',
            self::Semiannual => 'Semestral',
            self::Yearly     => 'Anual',
        };
    }
}