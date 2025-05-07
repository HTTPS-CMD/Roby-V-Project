<?php

namespace App\Enums;

enum OperatorEnum: string
{
    case All = 'all';
    case Hamrah = 'hamrah';
    case Irancell = 'irancell';
    case Rigthtel = 'rigthtel';
    case Shatel = 'shatel';
    case TCI = 'tci';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
