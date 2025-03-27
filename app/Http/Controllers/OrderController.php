<?php

namespace App\Http\Controllers;

use App\DAOs\OrderDAO;
use App\DTOs\OrderDTO;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderDAO;

    public function __construct(OrderDAO $orderDAO)
    {
        $this->orderDAO = $orderDAO;
    }

    public function store(Request $request)
    {
        $request->validate([
            'plants' => 'required|array',
            'plants.*.slug' => 'required|string|exists:plants,slug',
            'plants.*.quantity' => 'required|integer|min:1',
        ]);

        $user = auth()->user();
        $order = $this->orderDAO->createOrder($user->id, $request->plants);
        $orderDTO = OrderDTO::fromModel($order);

        return response()->json($orderDTO->toArray(), 201);
    }

    public function show(int $id)
{
    $user = auth()->user();
    $order = $this->orderDAO->getUserOrder($user->id, $id);
    $orderDTO = OrderDTO::fromModel($order);

    return response()->json($orderDTO->toArray());
}

public function destroy(int $id)
{
    $user = auth()->user();
    $this->orderDAO->cancelOrder($user->id, $id);

    return response()->json(['message' => 'Order cancelled'], 200);
}

public function markPrepared(int $id)
{
    $order = $this->orderDAO->markOrderAsPrepared($id);
    $orderDTO = OrderDTO::fromModel($order);
    return response()->json($orderDTO->toArray());
}

public function markReady(int $id)
{
    $order = $this->orderDAO->markOrderAsReady($id);
    $orderDTO = OrderDTO::fromModel($order);
    return response()->json($orderDTO->toArray());
}

public function stats()
{
    $stats = $this->orderDAO->getSalesStats();
    return response()->json($stats);
}
}