<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Services\AddressService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AddressController extends Controller
{

    public function __construct(protected AddressService $addressService) {}

    public function index(): JsonResponse
    {
        $addresses = $this->addressService->getAllAddress(JWTAuth::user()->id);

        return response()->json($addresses, 200);
    }

    public function store(StoreAddressRequest $request): JsonResponse
    {
        $validateData = $request->validated();

        $validateData['user_id'] = JWTAuth::user()->id;

        $address = $this->addressService->createAddress($validateData);

        return response()->json([
            'address' => $address
        ], 201);
    }

    public function update(UpdateAddressRequest $request, int $id): JsonResponse
    {
        $validateData = $request->validated();
        $userId = JWTAuth::user()->id;
        $validateData['user_id'] = $userId;

        $address = $this->addressService->updateAddress($validateData, $id, $userId);

        return response()->json($address, 200);
    }

    public function destroy(int $id): Response
    {
        $userId = JWTAuth::user()->id;
        $this->addressService->deleteAddress($id, $userId);

        return response()->noContent();
    }
}
