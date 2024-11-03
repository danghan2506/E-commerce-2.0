<?php

namespace App\Filament\Resources\CustomerResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            // Stat::make('Processing Orders', Order::query()->where('status', 'processing')->count()),
            // Stat::make('Shipped Orders', Order::query()->where('status', 'shipped')->count()),
            // Stat::make('Delivered Orders', Order::query()->where('status', 'delivered')->count()),
            // Stat::make('Cancelled Orders', Order::query()->where('status', 'cancelled')->count()),
            // Stat::make('Average Total Price Orders', !(empty(Order::query()->avg('grand_total'))) ? Number::currency(Order::query()->avg('grand_total'), 'VND') : '-'),
            Stat::make('New Orders', Order::query()->where('status', 'new')->count()),
            Stat::make('Order Processing', Order::query()->where('status', 'processing')->count()),
            Stat::make('Order Shipped', Order::query()->where('status', 'shipped')->count()),
            Stat::make('Average Price', function () {
                return Number::currency(Order::query()->avg('grand_total'), 'VND');
            }),
            
        ];
    }
}
