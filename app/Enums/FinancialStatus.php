<?php

namespace App\Enums;

enum FinancialStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case RECEIVED = 'received';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING   => 'Pendente',
            self::PAID      => 'Pago',
            self::RECEIVED  => 'Recebido',
            self::CANCELLED => 'Cancelado',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING   => 'yellow',
            self::PAID      => 'green',
            self::RECEIVED  => 'green',
            self::CANCELLED => 'red',
        };
    }
}