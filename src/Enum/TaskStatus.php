<?php

namespace App\Enum;

enum TaskStatus: string
{
    // Label in BDD
    case TODO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function getLabel(): string
    {
        // Label in templates
        return match ($this) {
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'Doing',
            self::DONE => 'Done',
        };
    }
}