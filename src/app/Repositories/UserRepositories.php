<?php

namespace App\Repositories;

use App\Models\User;

class UserRepositories
{

    public function findUserWithEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function userIsAdmin(int $id)
    {
        $user = User::findOrFail($id);
        return $user->role === 'admin';
    }

    public function userIsAdminOrModerator(int $id)
    {
        $user = User::findOrFail($id);

        if($user->role === 'client') {
            return null;
        }

        return $user->role;
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function getUser(int $id)
    {
        return User::findOrFail($id);
    }

    public function updateUser(array $data, int $id)
    {
        $user = $this->getUser($id);

        $user->update($data);
        return $user;
    }

    public function deleteUser(int $id)
    {
        return User::destroy($id);
    }
}
