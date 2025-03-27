<?php

namespace App\DAOs;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;

class PlantDAO
{
    public function getAllAvailablePlants(): Collection
    {
        return Plant::with('category')->get();
    }

    public function getPlantBySlug(string $slug): ?Plant
    {
        return Plant::with('category')->where('slug', $slug)->firstOrFail();
    }
}