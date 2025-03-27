<?php

namespace App\DTOs;

class OrderDTO
{
    public int $id;
    public array $plants;
    public string $status;

    public function __construct($order)
    {
        $this->id = $order->id;
        $this->plants = $order->plants->map(fn($plant) => [
            'slug' => $plant->slug,
            'quantity' => $plant->pivot->quantity,
        ])->toArray();
        $this->status = $order->status;
    }

    public static function fromModel($order): self
    {
        return new self($order);
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'plants' => $this->plants,
            'status' => $this->status,
        ];
    }
}