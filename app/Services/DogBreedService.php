<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DogBreedService
{
    protected $baseUrl;

    public function __construct($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        
        Log::info("Dog Breed Service initialized with base url: {$baseUrl}");
    }

    public function getBreeds() 
    {
        $response = Http::get("{$this->baseUrl}/breeds");

        if ($response->successful()) {
            $data = $response->json();
            
            # Sanitize and filter the response data:
            # - Removing any character not deemed safe.
            # - Selecting only the data from the input that we use
            foreach ($data as $key => $value) {
                $data[$key]['name'] = $this->sanitizeString($value['name']);
                if (isset($value['temperament']) && $value['temperament'] !== null) {
                    $data[$key]['temperament'] = $this->sanitizeString($value['temperament']);
                } else {
                    $data[$key]['temperament'] = '';
                }
            }
            
            return $data;
        }

        // Handle unsuccessful response
        return null;
    }

    private function sanitizeString($string) {
        // Define a regular expression for allowed characters
        $allowedCharsPattern = "/[^a-zA-Z0-9\(\)\-\,\.\!\ ]/";

        // Replace disallowed characters with an empty string
        return preg_replace($allowedCharsPattern, '', $string);
    }
}
