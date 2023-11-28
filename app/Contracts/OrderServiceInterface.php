<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DTO\OrderDTO;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;

interface OrderServiceInterface
{
    public function createOrder(OrderDTO $orderDTO): Order;

    public function getOrder(int $userId, int $orderId): Order;

    public function checkRentalOrdersToFinish(): void;

    public function extendRentalOrder(int $orderId, $rentHour): Order;

    public function getOrderHistory(int $userId): Collection;
}
