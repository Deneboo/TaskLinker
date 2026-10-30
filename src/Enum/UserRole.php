<?php

namespace App\Enum;

enum UserRole: string
{
    // Label in BDD
    case Manager = 'ROLE_ADMIN';
    case Employee = 'ROLE_USER';

    public function getLabel(): string
    {
        // Label in templates (but usually, it is template responsability to put capitalize label or no, we just send the database label to the template)
        return match ($this) {
            self::Manager => 'Manager',
            self::Employee => 'Employee',
        };
    }
}
