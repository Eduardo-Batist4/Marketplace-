<?php

namespace App\Services;

use App\Repositories\AddressRepositories;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AddressService
{

    public function __construct(protected AddressRepositories $addressRepositories) {}

    public function getAllAddress(int $id)
    {
        try {
            $address = $this->addressRepositories->getAllAddress($id);

            if (!$address) {
                return response()->json('No records found.', 200);
            }

            return $address;
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function createAddress(array $data)
    {
        try {
            return $this->addressRepositories->createAddress($data);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function updateAddress(array $data, string $id, int $user_id)
    {
        try {
            $address = $this->addressRepositories->getAddress($id);

            if ($address->user_id != $user_id) {
                throw new HttpException(403, 'Access denied.');
            }

            return $this->addressRepositories->updateAddress($data, $id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function deleteAddress(string $id, int $user_id)
    {
        try {
            $address = $this->addressRepositories->getAddress($id);

            if ($address->user_id != $user_id) {
                throw new HttpException(403, 'Access denied.');
            }

            return $this->addressRepositories->deleteAddress($id);
        } catch (\Exception $error) {
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
