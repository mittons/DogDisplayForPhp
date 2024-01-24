<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use App\Services\DogBreedService;

class E2ERoutesTest extends TestCase
{
    /**
     * @test
     * 
     * On success
     * 
     * Test if the index route behaves properly in isolation from other services.
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
     * Test if the application behaves properly on the renderBreeds route in isolation from external sources.
     * - In this case: When the mocked external sources return valid data.
     * 
     * We intercept a call to [DogBreedService] and return valid data.
     * 
     * The route should return:
     *  - A 200 status
     *  - View rendered should be 'dog_breeds_partial'
     *  - View should contain our mock data
     *  - View should display each part of our mock data we want on display
     */
    public function testRenderBreedsWithValidData()
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

        // $this->assertMatchesRegularExpression('/<div slot="headline" class="card-headline-text">*Shiba Inu*<\/div>/', $response->getContent());

        // $this->assertMatchesRegularExpression('/<div slot="supporting-text" class="card-supporting-text">.*Friendly*<\/div>/', $response->getContent());

    //     <div slot="headline" class="card-headline-text">
    //     <strong>{{ $item['name'] }}</strong>
    // </div>
    // <div slot="supporting-text" class="card-supporting-text">
    //     {{ $item['temperament'] }}
    // </div> 


    //error path

    
    }

    /**
     * @test
     * 
     * On failure
     * 
     * Test if the application behaves properly on the renderBreeds route in isolation from external sources.
     * - In this case: When the mocked external sources return no data.
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
