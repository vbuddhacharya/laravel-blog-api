<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case AUTHOR = 'author';

    public function label(): string
    {
        return match ($this) {
            RoleEnum::ADMIN => 'Administrator',
            RoleEnum::AUTHOR => 'Author',
        };
    }
}
