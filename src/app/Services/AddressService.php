<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\ResourceNotFoundException;
use App\Models\Address;
use Illuminate\Database\Eloquent\Collection;

class AddressService
{
    public function getAllAddress($id): Collection
    {
        $addresses = Address::where('user_id', $id)->get();

        if (!$addresses) {
            throw new ResourceNotFoundException();
        }

        return $addresses;
    }

    public function createAddress(array $data): Address
    {
        return Address::create($data);
    }

    public function updateAddress(array $data, string $id, int $user_id): Address
    {
        $address = Address::findOrFail($id);

        if ($address->user_id != $user_id) {
            throw new AccessDeniedException();
        }

        $address->update($data);
        return $address->fresh();
    }

    public function deleteAddress(string $id, int $user_id): bool
    {
        $address = Address::findOrFail($id);

        if ($address->user_id != $user_id) {
            throw new AccessDeniedException();
        }

        return $address->delete();
    }
}
