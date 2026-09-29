<?php

namespace App\Enum;

enum UserContract: string
{
    // Label in BDD
    case CDI = 'cdi';
    case CDD = 'cdd';
    case Freelance = 'freelance';

    public function getLabel(): string
    {
        // Label in templates (but usually, it is template responsability to put capitalize label or no, we just send the database label to the template)
        return match ($this) {
            self::CDI => 'CDI',
            self::CDD => 'CDD',
            self::Freelance => 'Freelance',
        };
    }
}