<?php

namespace App\Http\Controllers;

use App\DAOs\PlantDAO;
use App\DTOs\PlantDTO;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    protected $plantDAO;

    public function __construct(PlantDAO $plantDAO)
    {
        $this->plantDAO = $plantDAO;
    }

    public function index()
    {
        $plants = $this->plantDAO->getAllAvailablePlants();
        $plantDTOs = $plants->map(fn($plant) => PlantDTO::fromModel($plant)->toArray());

        return response()->json($plantDTOs);
    }

    public function show(string $slug)
    {
        $plant = $this->plantDAO->getPlantBySlug($slug);
        $plantDTO = PlantDTO::fromModel($plant)->toArray();

        return response()->json($plantDTO);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'images' => 'nullable|array',
            'category_id' => 'required|exists:categories,id',
        ]);
        $plant = $this->plantDAO->create($request->all());
        return response()->json(PlantDTO::fromModel($plant)->toArray(), 201);
    }

    public function update(Request $request, int $id)
    {
        $request->validate([
            'name' => 'string',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'images' => 'nullable|array',
            'category_id' => 'exists:categories,id',
        ]);
        $plant = $this->plantDAO->update($id, $request->all());
        return response()->json(PlantDTO::fromModel($plant)->toArray());
    }

    public function destroy(int $id)
    
    {
        $this->plantDAO->delete($id);
        return response()->json(['message' => 'Plant deleted']);
    }

}