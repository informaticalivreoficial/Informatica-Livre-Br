<?php

namespace App\Enums;

enum RecorrenciaStatus: string
{
    case Ativa = 'ativa';
    case Pausada = 'pausada';
    case Cancelada = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::Ativa => 'Ativa',
            self::Pausada => 'Pausada',
            self::Cancelada => 'Cancelada',
        };
    }
}
