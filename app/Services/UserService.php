<?php

namespace App\Services;

use App\Enums\RoleEnum;
use App\Models\User;

class UserService
{
    public function store(array $data) // to create user from both register (auth) and store (user) methods
    {
        $user = User::create(array_merge(
            $data,
            [
                'role' => RoleEnum::AUTHOR,
                'created_by' => auth()->id() ?? null,
            ]
        ));

        return $user;
    }
}
