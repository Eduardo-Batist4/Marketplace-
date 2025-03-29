<?php

namespace App\Services;

use App\Repositories\UserRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

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

    public function getUser(int $id, int $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(401, 'You do not have permission to get this profile.');
        }

        return $this->userRepositories->getUser($id);
    }

    public function updateUser($data, $id, $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(401, 'You do not have permission to modify this resource.');
        }

        return $this->userRepositories->updateUser($data, $id);
    }

    public function updateUserAdmin($data, $id, $user_id)
    {
        if (!$this->userRepositories->userIsAdmin($user_id)) {
            throw new HttpException(403, 'No permission!');
        }

        return $this->userRepositories->updateUser($data, $id);
    }

    public function deleteUser(int $id, int $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(401, 'You do not have permission to delete this profile.');
        }

        return $this->userRepositories->deleteUser($id);
    }
}
