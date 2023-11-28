<?php

declare(strict_types=1);

namespace App\Services\OrderService;

use App\Contracts\OrderServiceInterface;
use App\DTO\OrderDTO;
use App\Exceptions\OrderException;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;


class OrderService implements OrderServiceInterface
{
    /**
     * @throws OrderException
     */
    public function createOrder(OrderDTO $orderDTO): Order
    {
        $order = new Order();
        $order->fill($orderDTO->toArray());
        $order->user_id = auth()->user()->getAuthIdentifier();

        if ($orderDTO->getType() == 'rent') {
            $order->status = 'process';
            $order->rent_start_date = Carbon::now();
        }

        DB::transaction(function () use ($order, $orderDTO) {
            $products = Product::query()->lockForUpdate()->findMany([$orderDTO->getProducts()]);

            $amount = 0;
            foreach ($products as $product) {
                if (!$product->product_count > 0) {
                    throw new OrderException('Not in stock', 400);
                }

                $product->product_count -= 1;
                if ($orderDTO->getType() == 'rent') {
                    $amount += $product->rent_price;
                } else {
                    $amount += $product->price;
                }

                $product->save();
            }

            $order->amount = $amount;
            $order->save();

            $order->products()->attach($orderDTO->getProducts());
            $order->save();
        });

        return $order;
    }

    /**
     * @throws OrderException
     */
    public function getOrder(int $userId, int $orderId): Order
    {
        $order = Order::where('id', $orderId)->where('user_id', $userId)->first();

        if (!$order) {
            throw new OrderException('Order with id: ' . $orderId . ' does not found', 404);
        }

        return $order;
    }

    public function checkRentalOrdersToFinish(): void
    {
        DB::transaction(function () {
            $orders = Order::where('status', 'process')->lockForUpdate()->get();

            foreach ($orders as $order) {
                $rentStartDate = Carbon::create($order->rent_start_date);
                $now = Carbon::now();
                $diff = $rentStartDate->diffInHours($now);

                if ($diff >= $order->rent_hour) {
                    $order->status = 'ended';

                    $products = $order->products()->lockForUpdate()->get();

                    foreach ($products as $product) {
                        $product->product_count += 1;
                        $product->save();
                    }

                    $order->save();
                }
            }
        });
    }

    public function getOrderHistory(int $userId): Collection
    {
        return Order::query()->where('user_id', $userId)->get();
    }

    /**
     * @throws OrderException
     */
    public function extendRentalOrder(int $orderId, $rentHour): Order
    {
        return DB::transaction(function () use ($orderId, $rentHour) {
            $order = Order::lockForUpdate()->where('id', $orderId)->where('type', 'rent')->where('status', '<>', 'ended')->where('user_id', auth()->user()->getAuthIdentifier())->first();

            if (!$order) {
                throw new OrderException('You dont have unfinished order with id: ' . $orderId, 404);
            }

            $rentStartDate = Carbon::create($order->rent_start_date);
            $now = Carbon::now();
            $diff = $rentStartDate->diffInHours($now);

            $allowedHours = 24 - $diff;

            if ($allowedHours <= $rentHour + $order->rent_hour) {
                throw new OrderException('Not allowed to extend rental order for more than 24 hours', 403);
            }

            $details = [];
            if ($order->details) {
                $details = json_decode($order->details, true);
            }

            $details['old_rent_hours'][] = $order->rent_hour;

            $order->details = $details;

            $order->rent_hour += $rentHour;

            $order->save();

            return $order;
        });
    }
}
