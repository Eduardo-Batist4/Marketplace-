<?php

namespace App\Repositories;

use App\Models\User;

class UserRepositories
{

    public function findUserWithEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser($data)
    {
        return User::create($data);
    }

}