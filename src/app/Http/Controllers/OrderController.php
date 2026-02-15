<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{

    public function __construct(protected OrderService $orderService) {}

    public function index(): AnonymousResourceCollection
    {
        $orders = $this->orderService->getAllOrder(JWTAuth::user()->id);
        return OrderResource::collection($orders);
    }

    public function getAllOrderEveryone(): AnonymousResourceCollection
    {
        $orders = $this->orderService->getAllOrderEveryone();
        return OrderResource::collection($orders);
    }

    public function store(StoreOrderRequest $request): OrderResource
    {
        $validateData = $request->validated();

        $order = $this->orderService->createOrder($validateData, JWTAuth::user()->id);

        return OrderResource::make($order);
    }

    public function update(UpdateOrderRequest $request, int $id): OrderResource
    {
        $validateData = $request->validated();

        $order = $this->orderService->updateStatus($validateData, $id, JWTAuth::user()->id);

        return OrderResource::make($order);
    }

    public function destroy(int $id): Response
    {
        $this->orderService->deleteOrder($id);

        return response()->noContent();
    }
}
