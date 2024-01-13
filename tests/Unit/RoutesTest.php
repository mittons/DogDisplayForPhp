<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DogBreedService;

class RoutesTest extends TestCase
{
    /**
     * @test
     * 
     * On success
     * 
     * Test if the index route behaves properly in isolation from external services.
     * 
     * The route should return:
     *  - A 200 status
     *  - A rendering of the view 'dog_screen_init'
     */
    public function testIndex()
    {
        $response = $this->get('/');

        // Assert that the response status is 200 OK
        $response->assertStatus(200);

        // Assert that the view rendered is the expected one
        $response->assertViewIs('dog_screen_init');

                //Verify our header element is in the html contained in the response body
                $this->assertMatchesRegularExpression('/div class="header-bar"/', $response->getContent());

                //Verify our dog breeds request button is in the html contained in the response body
                $this->assertMatchesRegularExpression('/id="dog-breed-list-request-button"/', $response->getContent());
    }

    /**
     * @test
     * 
     * On success
     * 
     * Test if the renderBreeds route behaves properly in conjunction with program but isolation from external services.
     * 
     * Mock http client intercepts a http request call to DogApi and returns 
     *   - - valid data repsonse (not exactly what we would get from the production service.. but the subset we are using right now). - -
     * 
     * The route should return:
     *  - A 200 status
     *  - View rendered should be 'dog_breeds_partial'
     *  - View should contain our mock data
     *  - View should display each part of our mock data we want on display
     */
    public function testRenderBreedsWithValidData()
    {

        // Setup: Mock data to be returned by DogBreedService
        $mockData = [
            ['name' => 'Shiba Inu', 'temperament' => 'Friendly'],
            ['name' => 'Pug', 'temperament' => 'Happy'],
            ['name' => 'Retriever', 'temperament' => 'Excited']
        ];

        // Arrange: Mock the DogBreedService
        $mock = $this->mock(DogBreedService::class, function ($mock) use ($mockData) {
            $mock->shouldReceive('getBreeds')->once()->andReturn($mockData);
        });

        // Act: Call the service
        $response = $this->get('/renderBreeds');

        // Assert: That the response status is 200 OK
        $response->assertStatus(200);

        // Assert: That the view rendered is the expected one
        $response->assertViewIs('dog_breeds_partial');

        // Assert: That the view has the expected data
        $response->assertViewHas('data', $mockData);

        // Assert: That the view displays the expected data
        foreach($mockData as $dogBreedData){
            $response->assertSee($dogBreedData['name']);
            $response->assertSee($dogBreedData['temperament']);
        }
    }

    /**
     * @test
     * 
     * On failure
     * 
     * Test if the renderBreeds route behaves properly when no data is returned from the service.
     * 
     * The route should return:
     *  - A 500 status
     *  - JSON response with an error message
     */
    public function testRenderBreedsWithNoData()
    {
        // Arrange: Mock the DogBreedService to return null
        $this->mock(DogBreedService::class, function ($mock) {
            $mock->shouldReceive('getBreeds')->once()->andReturn(null);
        });

        // Act: Call the route
        $response = $this->get('/renderBreeds');

        // Assert: That the response status is 500
        $response->assertStatus(500);

        // Assert: That the response is a JSON with the expected error message
        $response->assertJson(['error' => 'Unable to fetch data from the external service']);
    }
}
