<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{

    public function __construct(protected AddressService $addressService) {}

    public function index()
    {
        $addresses = $this->addressService->getAllAddress(Auth::id());

        return response()->json($addresses, 200);
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'street' => 'required|string|min:5|max:200',
            'number' => 'required|numeric',
            'zip' => 'required|string|min:7',
            'city' => 'required|string|min:4',
            'state' => 'required|string|min:4',
            'country' => 'required|string|min:4'
        ]);

        $validateData['user_id'] = Auth::id();

        $address = $this->addressService->createAddress($validateData);

        return response()->json([
            'message' => 'Address successfully created!',
            'address' => $address
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        $validateData = $request->validate([
            'street' => 'sometimes|string|min:5|max:200',
            'number' => 'sometimes|numeric',
            'zip' => 'sometimes|string|min:7',
            'city' => 'sometimes|string|min:4',
            'state' => 'sometimes|string|min:4',
            'country' => 'sometimes|string|min:4'
        ]);

        $validateData['user_id'] = Auth::id();

        $address = $this->addressService->updateAddress($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'Successfully updated!',
            'address' => $address
        ], 200);
    }

    public function destroy(string $id)
    {
        $this->addressService->deleteAddress($id, Auth::id());

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204); 
    }
}
