<?php

namespace App\Enum;

enum UserContract: string
{
    case CDI = 'cdi';
    case CDD = 'cdd';
    case Freelance = 'freelance';

    public function getLabel(): string
    {
        return match ($this) {
            self::CDI => 'CDI',
            self::CDD => 'CDD',
            self::Freelance => 'Freelance',
        };
    }
}