<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Services\AddressService;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{

    public function __construct(protected AddressService $addressService) {}

    public function index()
    {
        $addresses = $this->addressService->getAllAddress(JWTAuth::user()->id);

        return response()->json($addresses, 200);
    }

    public function store(StoreAddressRequest $request)
    {
        $validateData = $request->validated();

        $validateData['user_id'] = JWTAuth::user()->id;

        $address = $this->addressService->createAddress($validateData);

        return response()->json([
            'message' => 'Successfully created!',
            'address' => $address
        ], 201);
    }

    public function update(UpdateAddressRequest $request, int $id)
    {
        $validateData = $request->validated();

        $validateData['user_id'] = JWTAuth::user()->id;

        $address = $this->addressService->updateAddress($validateData, $id, JWTAuth::user()->id);

        return response()->json([
            'message' => 'Successfully updated!',
            'address' => $address
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->addressService->deleteAddress($id, JWTAuth::user()->id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204); 
    }
}
