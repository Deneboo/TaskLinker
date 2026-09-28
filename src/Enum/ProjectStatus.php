<?php

namespace App\Enum;

enum ProjectStatus: string
{
    case IN_PROGESS = 'in_progress';
    case ARCHIVED = 'archived';

    public function getLabel(): string
    {
        return match ($this) {
            self::IN_PROGESS => 'En cours',
            self::ARCHIVED => 'Archivé',
        };
    }
}