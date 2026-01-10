<?php

namespace App\Enums;

enum BillingInterval: string {
    case Monthly     = 'monthly';
    case Quarterly   = 'quarterly';
    case Semiannual  = 'semiannual';
    case Yearly      = 'yearly';

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