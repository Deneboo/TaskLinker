<?php

namespace App\Enum;

enum TaskStatus: string
{
    case TODO = 'to do';
    case IN_PROGRESS = 'doing';
    case DONE = 'done';

    public function getLabel(): string
    {
        return match ($this) {
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Done',
        };
    }
}