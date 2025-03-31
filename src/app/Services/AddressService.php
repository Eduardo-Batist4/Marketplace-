<?php

namespace App\Services;

use App\Repositories\AddressRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AddressService
{

    public function __construct(protected AddressRepositories $addressRepositories) {}

    public function getAllAddress(int $id)
    {
        $address = $this->addressRepositories->getAllAddress($id);
        
        if (!$address) {
            return response()->json('No registered address!');
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

        if($address->user_id != $user_id) {
            throw new HttpException(401, 'You do not have permission to modify this resource.');
        }

        return $this->addressRepositories->updateAddress($data, $id);
    }

    public function deleteAddress(string $id, int $user_id)
    {
        $address = $this->addressRepositories->getAddress($id);

        if($address->user_id != $user_id) {
            throw new HttpException(401, 'You do not have permission to delete this address.');
        }
        
        return $this->addressRepositories->deleteAddress($id);
    }
}
