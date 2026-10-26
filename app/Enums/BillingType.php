<?php

namespace App\Enums;

enum BillingType: string {
    case ONE_TIME = 'one_time';
    case RECURRING = 'recurring';

    public function label(): string
    {
        return match ($this) {
            self::ONE_TIME  => 'Pagamento único',
            self::RECURRING => 'Recorrente',
        };
    }

    public function isRecurring(): bool
    {
        return $this === self::RECURRING;
    }
}
