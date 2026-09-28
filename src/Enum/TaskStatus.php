<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'to_do';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public function getLabel(): string
    {
        return match ($this) {
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'Doing',
            self::DONE => 'Done',
        };
    }
}