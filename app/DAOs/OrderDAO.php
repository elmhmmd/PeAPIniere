<?php

namespace App\DAOs;

use App\Models\Order;
use App\Models\Plant;
use Illuminate\Support\Facades\DB;

class OrderDAO
{
    public function createOrder(int $userId, array $plantData): Order
    {
        $order = Order::create(['user_id' => $userId, 'status' => 'pending']);
        
        foreach ($plantData as $item) {
            $plant = Plant::where('slug', $item['slug'])->firstOrFail();
            $order->plants()->attach($plant->id, ['quantity' => $item['quantity']]);
        }

        return $order->load('plants');
    }

    public function getUserOrder(int $userId, int $orderId): Order
{
    return Order::where('user_id', $userId)
                ->where('id', $orderId)
                ->with('plants')
                ->firstOrFail();
}

public function cancelOrder(int $userId, int $orderId): void
{
    $order = Order::where('user_id', $userId)
                  ->where('id', $orderId)
                  ->where('status', 'pending')
                  ->firstOrFail();
    $order->delete();
}

public function markOrderAsPrepared(int $orderId): Order
{
    $order = Order::findOrFail($orderId);
    $order->update(['status' => 'prepared']);
    return $order->load('plants');
}

public function markOrderAsReady(int $orderId): Order
{
    $order = Order::findOrFail($orderId);
    $order->update(['status' => 'delivered']);
    return $order->load('plants');
}

public function getSalesStats(): array
{
    $totalSales = DB::table('orders')
        ->where('status', 'delivered')
        ->count();

    $popularPlants = DB::table('order_plant')
        ->join('plants', 'order_plant.plant_id', '=', 'plants.id')
        ->join('orders', 'order_plant.order_id', '=', 'orders.id')
        ->where('orders.status', 'delivered')
        ->select('plants.name', 'plants.slug', DB::raw('SUM(order_plant.quantity) as total_quantity'))
        ->groupBy('plants.id', 'plants.name', 'plants.slug')
        ->orderByDesc('total_quantity')
        ->limit(5)
        ->get();

    $categoryDistribution = DB::table('order_plant')
        ->join('plants', 'order_plant.plant_id', '=', 'plants.id')
        ->join('categories', 'plants.category_id', '=', 'categories.id')
        ->join('orders', 'order_plant.order_id', '=', 'orders.id')
        ->where('orders.status', 'delivered')
        ->select('categories.category_name', DB::raw('SUM(order_plant.quantity) as total_quantity'))
        ->groupBy('categories.id', 'categories.category_name')
        ->get();

    return [
        'total_sales' => $totalSales,
        'popular_plants' => $popularPlants,
        'category_distribution' => $categoryDistribution,
    ];
}
}