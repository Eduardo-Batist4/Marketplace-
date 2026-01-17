<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{

    public function findUserWithEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getUser(int $id): User
    {
        $user = User::findOrFail($id);
        return $user->load('address', 'cart');
    }

    public function getAllUsers(int $userId): User
    {
        $user = User::findOrFail($userId);
        return $user->load('address', 'cart');

    }

    public function createUser(array $data): User
    {
        return User::create($data);
    }

    public function updateUser(array $data, int $id): User
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user->fresh();
    }

    public function updateUserImage(UploadedFile $image, int $id)
    {
        $user = User::findOrFail($id);

        if ($user->image_path) {
            Storage::disk('public')->delete($user->image_path);
        }

        $imageName = Str::uuid() . '.' . $image->getClientOriginalExtension();

        $path = $image->storeAs('profiles', $imageName, 'public');

        $user->update([
            'image_path' => $path,
        ]);

        return $user->fresh();
    }

    public function deleteUser(int $id): bool
    {
        $user = User::findOrFail($id);
        return $user->delete();
    }
}
