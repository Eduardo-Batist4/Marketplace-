<?php

namespace App\Services;

use App\Repositories\UserRepositories;

class UserService
{

    public function __construct(protected UserRepositories $userRepositories) {}

    public function findUserWithEmail($email)
    {
        return $this->userRepositories->findUserWithEmail($email);;
    }

    public function createUser(array $data)
    {
        return $this->userRepositories->createUser($data);
    }
}
