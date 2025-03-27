<?php

namespace App\DAOs;

use App\Models\Plant;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

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

    public function create(array $data): Plant
    {
        return Plant::create($data);
    }

    public function update(int $id, array $data): Plant
    {
        $plant = Plant::findOrFail($id);
        $plant->update($data);
        return $plant->load('category');
    }

    public function delete(int $id): void
    {
        DB::table('plants')->where('id', $id)->delete();
    }

}