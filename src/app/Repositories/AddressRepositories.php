<?php

namespace App\Repositories;

use App\Models\Address;

class AddressRepositories
{
    
    public function getAllAddress(int $id) 
    {
        return Address::where('user_id', $id)->get();
    }

    public function createAddress(array $data)
    {
        return Address::create($data);
    }

    public function getAddress(int $id)
    {
        return Address::findOrFail($id);        
    }

    public function updateAddress(array $data, int $id) 
    {
        $address = $this->getAddress($id);

        $address->update($data);
        return $address;
    }

    public function deleteAddress(int $id) 
    {
        return Address::destroy($id);
    }
}
