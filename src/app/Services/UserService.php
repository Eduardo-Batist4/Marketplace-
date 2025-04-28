<?php

namespace App\Services;

use App\Repositories\UserRepositories;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Str;

class UserService
{

    public function __construct(protected UserRepositories $userRepositories) {}

    public function findUserWithEmail($email)
    {
        return $this->userRepositories->findUserWithEmail($email);
    }

    public function createUser(array $data)
    {
        return $this->userRepositories->createUser($data);
    }

    public function getUser(int $id, int $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->userRepositories->getUser($id)->load('address', 'cart');
    }

    public function updateUser($data, $id, $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->userRepositories->updateUser($data, $id);
    }

    public function updateUserAdmin($data, $id)
    {
        return $this->userRepositories->updateUser($data, $id); 
    }

    public function updateUserImage($request, $id, $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(403, 'Access denied.');
        }

        if ($user->image_path) {
            Storage::delete('public/' . $user->image_path);
        }

        $imageName = Str::uuid() . '.' . $request->file('image_path')->getClientOriginalExtension();

        $path = Storage::putFileAs('public/profiles', $request->file('image_path'), $imageName);

        return $this->userRepositories->updateUser(['image_path' => $path], $id);
    }

    public function deleteUser(int $id, int $user_id)
    {
        $user = $this->userRepositories->getUser($id);

        if($user->id !== $user_id) {
            throw new HttpException(403, 'Access denied.');
        }

        return $this->userRepositories->deleteUser($id);
    }
}
