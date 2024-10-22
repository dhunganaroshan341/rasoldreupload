<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\OurServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientServiceControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_stores_client_service_with_service_price_when_amount_is_not_provided()
    {
        // Arrange: Create a client and a service
        $client = Client::factory()->create(); // Assumes you have a factory for Client
        $service = OurServices::factory()->create([
            'price' => 100.00, // Define a price for the service
        ]);

        // Act: Send a POST request to store a new client service without an amount
        $response = $this->post(route('ClientServices.store'), [
            'client_id' => $client->id,
            'service_id' => $service->id,
            'duration' => 12,
            'duration_type' => 'months',
            'hosting_service' => 'yes',
            'email_service' => 'no',
            'name' => 'Test Service',
            'description' => 'Test Description',
            'amount' => '', // Amount is empty to test the default behavior
        ]);

        // Assert: Check if the service was stored with the service's price as amount
        $response->assertRedirect(route('ClientServices.index', ['client_id' => $client->id]));
        $response->assertSessionHas('success', 'Client service created successfully.');

        // Retrieve the newly created client service
        $clientService = ClientService::where('client_id', $client->id)
            ->where('service_id', $service->id)
            ->first();

        $this->assertNotNull($clientService);
        $this->assertEquals($service->price, $clientService->amount);
    }

    /** @test */
    public function it_stores_client_service_with_provided_amount()
    {
        // Arrange: Create a client and a service
        $client = Client::factory()->create(); // Assumes you have a factory for Client
        $service = OurServices::factory()->create([
            'price' => 200.00, // Define a price for the service
        ]);

        // Act: Send a POST request to store a new client service with a specific amount
        $response = $this->post(route('ClientServices.store'), [
            'client_id' => $client->id,
            'service_id' => $service->id,
            'duration' => 6,
            'duration_type' => 'months',
            'hosting_service' => 'no',
            'email_service' => 'yes',
            'name' => 'Another Test Service',
            'description' => 'Another Test Description',
            'amount' => 150.00, // Providing an amount
        ]);

        // Assert: Check if the service was stored with the provided amount
        $response->assertRedirect(route('ClientServices.index', ['client_id' => $client->id]));
        $response->assertSessionHas('success', 'Client service created successfully.');

        // Retrieve the newly created client service
        $clientService = ClientService::where('client_id', $client->id)
            ->where('service_id', $service->id)
            ->first();

        $this->assertNotNull($clientService);
        $this->assertEquals(150.00, $clientService->amount);
    }
}
