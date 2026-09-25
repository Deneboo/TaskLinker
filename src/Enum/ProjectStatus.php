<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case ACTIVE = 'en cours';
    case ARCHIVED = 'archivé';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::ARCHIVED => 'Archived',
        };
    }
}