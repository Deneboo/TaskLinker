<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case Active = 'en cours';
    case Archived = 'archivé';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Archived => 'Archived',
        };
    }
}