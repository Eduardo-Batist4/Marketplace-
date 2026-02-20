<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\Services\AddressService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{

    public function __construct(protected AddressService $addressService) {}

    public function index(): AnonymousResourceCollection
    {
        $addresses = $this->addressService->getAllAddress(JWTAuth::user()->id);

        return AddressResource::collection($addresses);
    }

    public function store(StoreAddressRequest $request): AddressResource
    {
        $validateData = $request->validated();

        $validateData['user_id'] = JWTAuth::user()->id;

        $address = $this->addressService->createAddress($validateData);

        return AddressResource::make($address);
    }

    public function update(UpdateAddressRequest $request, int $id): AddressResource
    {
        $validateData = $request->validated();
        $userId = JWTAuth::user()->id;
        $validateData['user_id'] = $userId;

        $address = $this->addressService->updateAddress($validateData, $id, $userId);

        return AddressResource::make($address);
    }

    public function destroy(int $id): Response
    {
        $userId = JWTAuth::user()->id;
        $this->addressService->deleteAddress($id, $userId);

        return response()->noContent();
    }
}
