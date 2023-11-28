<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Contracts\OrderServiceInterface;
use Illuminate\Console\Command;

class checkRentalOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-rental-order-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(OrderServiceInterface $orderService): void
    {
        $orderService->checkRentalOrdersToFinish();
    }
}
