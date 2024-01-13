<?php

namespace App\Http\Controllers;

use App\Services\DogBreedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DogBreedController extends Controller
{
    private $dogBreedService;

    public function __construct(DogBreedService $dogBreedService)
    {
        // Initialize the external data service
        $this->dogBreedService = $dogBreedService;
    }

    public function index()
    {
        return view('dog_screen_init');
    }

    public function renderBreeds()
    {
        // Fetch data from the external service
        $data = $this->dogBreedService->getBreeds();
        
        if ($data) {
            // Return a partial view that contains the dynamic content
            return view('dog_breeds_partial', ['data' => $data]);
        } else {
            // Handle errors appropriately
            return response()->json(['error' => 'Unable to fetch data from the external service'], 500);
        }
    }
}
