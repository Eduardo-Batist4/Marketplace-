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
        try {
            return $this->userRepositories->findUserWithEmail($email);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createUser(array $data)
    {
        try {
            return $this->userRepositories->createUser($data);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getUser(int $id, int $user_id)
    {
        try {
            $user = $this->userRepositories->getUser($id);
    
            if($user->id !== $user_id) {
                throw new HttpException(403, 'Access denied.');
            }
    
            return $this->userRepositories->getUser($id)->load('address', 'cart');
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateUser($data, $id, $user_id)
    {
        try {
            $user = $this->userRepositories->getUser($id);
    
            if($user->id !== $user_id) {
                throw new HttpException(403, 'Access denied.');
            }
    
            return $this->userRepositories->updateUser($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateUserAdmin($data, $id)
    {
        try {
            return $this->userRepositories->updateUser($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateUserImage($request, $id, $user_id)
    {
        try {
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
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteUser(int $id, int $user_id)
    {
        try {
            $user = $this->userRepositories->getUser($id);
    
            if($user->id !== $user_id) {
                throw new HttpException(403, 'Access denied.');
            }
    
            return $this->userRepositories->deleteUser($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
