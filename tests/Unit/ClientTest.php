<?php

namespace Tests\Unit;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_client_with_hosting_and_email_services()
    {
        // Simulate form data with hosting and email services
        $formData = [
            'name' => 'John Doe',
            'service_type' => 1, // Assuming you have a service type with ID 1
            'hosting_service' => 'host.hosting.com',
            'email_service' => 'email@domain.com',
            'address' => '123 Main St',
            'pan_no' => '1234567890',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'services' => [1, 2], // Assuming you have services with ID 1 and 2
        ];

        // Make a post request to store the client
        $response = $this->post(route('clients.store'), $formData);

        // Assert the client was created
        $this->assertDatabaseHas('clients', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'hosting_service' => 'host.hosting.com',
            'email_service' => 'email@domain.com',
        ]);

        // Assert the redirection
        $response->assertRedirect(route('clients.index'));
    }

    /** @test */
    public function it_can_update_a_client_with_hosting_and_email_services()
    {
        // Create a client with initial data
        $client = Client::factory()->create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'hosting_service' => 'old.hosting.com',
            'email_service' => 'oldemail@domain.com',
        ]);

        // Simulate form data for update with new hosting and email services
        $formData = [
            'name' => 'Jane Smith',
            'service_type' => 1, // Assuming you have a service type with ID 1
            'hosting_service' => 'new.hosting.com',
            'email_service' => 'newemail@domain.com',
            'address' => '456 Elm St',
            'pan_no' => '0987654321',
            'email' => 'jane.smith@example.com',
            'phone' => '+1987654321',
            'services' => [2, 3], // Update the services
        ];

        // Make a put request to update the client
        $response = $this->put(route('clients.update', $client->id), $formData);

        // Assert the client was updated with new hosting and email services
        $this->assertDatabaseHas('clients', [
            'name' => 'Jane Smith',
            'email' => 'jane.smith@example.com',
            'hosting_service' => 'new.hosting.com',
            'email_service' => 'newemail@domain.com',
        ]);

        // Assert the redirection
        $response->assertRedirect(route('clients.index'));
    }

    /** @test */
    public function it_validates_client_form_data_including_hosting_and_email_services()
    {
        // Simulate invalid form data (e.g., missing required fields, invalid hosting and email services)
        $formData = [
            'name' => '', // Name is required
            'service_type' => null,
            'hosting_service' => '', // Hosting service is required
            'email_service' => '', // Email service is required
            'address' => '',
            'pan_no' => '',
            'email' => 'invalid-email', // Invalid email format
            'phone' => '',
            'services' => [],
        ];

        // Make a post request to store the client
        $response = $this->post(route('clients.store'), $formData);

        // Assert validation errors
        $response->assertSessionHasErrors([
            'name',
            'hosting_service',
            'email_service',
            'email',
            'phone',
        ]);
    }
}
