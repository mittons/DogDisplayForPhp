<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\DogBreedService;

class DogBreedServiceTest extends TestCase
{
    /**
     * @test
     * 
     * On success
     * 
     * Test if the getBreeds service layer function behaves properly in isolation from other dependenices/services.
     * Mock http client intercepts a http request call to DogApi and returns 
     *   - - valid data repsonse (not exactly what we would get from the production service.. but the subset we are using right now). - -
     * 
     * The getBreeds function should return the data without question.
     */
    public function testGetBreedsReturnsDataOnSuccess()
    {
        // Setup: Mock data to be returned in HTML Response
        $mockData = [
            ['name' => 'Shiba Inu', 'temperament' => 'Friendly'],
            ['name' => 'Pug', 'temperament' => 'Happy'],
            ['name' => 'Retriever', 'temperament' => 'Excited']
        ];

        // Arrange: Mock the HTTP response
        Http::fake([
            '*/breeds' => Http::response($mockData, 200),
        ]);

        // Act: Instantiate the Service Instance
        $service = new DogBreedService('http://dummyurl');

        // Act: Call the method
        $response = $service->getBreeds();

        // Assert: Check if the response is as expected
        $this->assertEquals($mockData, $response);
    }

    /**
     * @test
     * 
     * On error
     * 
     * Test if the getBreeds service layer function behaves properly in isolation from other dependenices/services.
     * Mock http client intercepts a http request call to DogApi and returns  
     *  - - an unsuccessful response - - 
     * 
     * The getBreeds function sould return null.
     */

    public function testGetDataReturnsNullOnFailure()
    {
        // Arrange: Mock the HTTP response to simulate failure
        Http::fake([
            '*/breeds' => Http::response([], 500),
        ]);
        
        // Act: Instantiate the Service Instance
        $service = new DogBreedService('http://dummyurl');

        // Act: Call the method
        $response = $service->getBreeds();

        // Assert: Check if the response is null
        $this->assertNull($response);
    }

    /**
     * @test
     * 
     * On error
     * 
     * Test Sanitation of Dog Breed Names and Temperaments
     *
     * This test verifies that the DogBreedService correctly sanitizes the data it retrieves.
     * It iterates over a set of unsafe characters and for each character, appends it to mock
     * data for 'name' and 'temperament'. The service is expected to sanitize these fields by
     * removing or replacing the unsafe characters.
     *
     * The test asserts that the returned data from the service does not contain any of the
     * unsafe characters, thereby ensuring the effectiveness of the sanitation process.
     *
     * @return void
     */
    public function testSanitation()
    {
        $unsafeChars = ['|', '&', ';', '<', '>', '$', '`', '\\', '"'];
        $mockData = ['name' => 'Test', 'temperament' => 'Aggressive'];

        foreach ($unsafeChars as $char) {
            $mockData['name'] = 'Test' . $char;
            $mockData['temperament'] = 'Aggressive' . $char;

            Http::fake([
                '*' => Http::response([$mockData], 200)
            ]);

            $service = new DogBreedService('http://dummyurl');
            $result = $service->getBreeds();


            $this->assertStringNotContainsString($char, $result[0]['name']);
            $this->assertStringNotContainsString($char, $result[0]['temperament']);
        }
    }
}
