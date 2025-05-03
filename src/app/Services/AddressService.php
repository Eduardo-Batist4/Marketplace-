<?php

namespace App\Services;

use App\Exceptions\AccessDeniedException;
use App\Exceptions\ResourceNotFoundException;
use App\Repositories\AddressRepositories;

class AddressService
{

    public function __construct(protected AddressRepositories $addressRepositories) {}

    public function getAllAddress(int $id)
    {
        $address = $this->addressRepositories->getAllAddress($id);

        if (!$address) {
            throw new ResourceNotFoundException();
        }

        return $address;
    }

    public function createAddress(array $data)
    {
        return $this->addressRepositories->createAddress($data);
    }

    public function updateAddress(array $data, string $id, int $user_id)
    {
        $address = $this->addressRepositories->getAddress($id);

        if ($address->user_id != $user_id) {
            throw new AccessDeniedException();
        }

        return $this->addressRepositories->updateAddress($data, $id);
    }

    public function deleteAddress(string $id, int $user_id)
    {
        $address = $this->addressRepositories->getAddress($id);

        if ($address->user_id != $user_id) {
            throw new AccessDeniedException();
        }

        return $this->addressRepositories->deleteAddress($id);
    }
}
