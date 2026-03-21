<?php

namespace App\Enums;

enum SubscriptionStatus: string {
    case ACTIVE   = 'active';
    case PAUSED   = 'paused';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE   => 'Ativa',
            self::PAUSED   => 'Pausada',
            self::CANCELED => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::ACTIVE   => 'green',
            self::PAUSED   => 'yellow',
            self::CANCELED => 'red',
        };
    }
}