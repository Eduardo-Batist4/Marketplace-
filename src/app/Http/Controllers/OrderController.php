<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService) {}

    public function index()
    {
        return $this->orderService->getAllOrder();
    }

    public function store(StoreOrderRequest $request)
    {
        $validateData = $request->validated();

        $order = $this->orderService->createOrder($validateData, Auth::id());

        return response()->json([
            'message' => 'Successfully placed!',
            'order' => $order
        ], 201);
    }

    public function update(UpdateOrderRequest $request, int $id)
    {
        $validateData = $request->validated();

        $order = $this->orderService->updateStatus($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'Successfully updated!',
            'order' => $order
        ], 200);
    }

    public function destroy(int $id)
    {
        $this->orderService->deleteOrder($id);

        return response()->json([
            'message' => 'Successfully deleted!',
        ], 204);
    }
}
