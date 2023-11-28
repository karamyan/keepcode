<?php

namespace App\Http\Controllers\Api;

use App\Contracts\OrderServiceInterface;
use App\DTO\OrderDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExtendRentalOrderRequest;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class OrderController extends Controller
{
    public function createOrder(StoreOrderRequest $request, OrderServiceInterface $orderService): OrderResource
    {
        $orderDTO = new OrderDTO(
            $request->get('type'),
            $request->get('products'),
            $request->get('rent_hour')
        );

        $order = $orderService->createOrder($orderDTO);

        return new OrderResource($order);
    }

    public function extendRentalOrder($orderId, ExtendRentalOrderRequest $request, OrderServiceInterface $orderService): OrderResource
    {
        $order = $orderService->extendRentalOrder($orderId, $request->get('rent_hour'));

        return new OrderResource($order);
    }

    public function getOrderHistory(OrderServiceInterface $orderService): AnonymousResourceCollection
    {
        $order = $orderService->getOrderHistory(auth()->user()->getAuthIdentifier());

        return OrderResource::collection($order);
    }

    public function getOrder(int $orderId, OrderServiceInterface $orderService): OrderResource
    {
        $order = $orderService->getOrder(auth()->user()->getAuthIdentifier(), $orderId);

        return new OrderResource($order);
    }
}
