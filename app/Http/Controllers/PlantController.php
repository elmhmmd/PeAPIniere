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
}