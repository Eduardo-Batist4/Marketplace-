<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService) {}

    public function index()
    {
        return $this->orderService->getAllOrder();
    }

    public function store(Request $request)
    {
        $validateData = $request->validate([
            'user_id' => 'sometimes|numeric|exists:users,id',
            'address_id' => 'required|numeric|exists:addresses,id',
            'coupon_id' => 'sometimes|numeric',
            'status' => 'sometimes|in:pending,processing,shipped,completed,canceled',
        ]);

        $order = $this->orderService->createOrder($validateData, Auth::id());

        return response()->json([
            'message' => 'Order successfully placed!',
            'order' => $order
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $validateData = $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,canceled',
        ]);

        $order = $this->orderService->updateStatus($validateData, $id, Auth::id());

        return response()->json([
            'message' => 'Order successfully updated!',
            'order' => $order
        ], 200);
    }

    public function destroy(int $id)
    {
        return response($this->orderService->deleteOrder($id), 204);
    }
}
