<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'name' => 'ElectroTech Ltd.',
                'client_type' => 'Electronics',
                'address' => '123 Tech Park, Silicon Valley',
                'email' => 'contact@electrotech.com',
                'phone' => '123-456-7890',
            ],
            [
                'name' => 'Garment World',
                'client_type' => 'Garments',
                'address' => '456 Fashion Street, New York',
                'email' => 'info@garmentworld.com',
                'phone' => '234-567-8901',
            ],
            [
                'name' => 'Foodies Hub',
                'client_type' => 'Food & Beverage',
                'address' => '789 Culinary Avenue, Chicago',
                'email' => 'support@foodieshub.com',
                'phone' => '345-678-9012',
            ],
            [
                'name' => 'HealthFirst',
                'client_type' => 'Healthcare',
                'address' => '101 Wellness Blvd, Los Angeles',
                'email' => 'service@healthfirst.com',
                'phone' => '456-789-0123',
            ],
            [
                'name' => 'AutoDrive',
                'client_type' => 'Automotive',
                'address' => '202 Motorway, Detroit',
                'email' => 'sales@autodrive.com',
                'phone' => '567-890-1234',
            ],
            [
                'name' => 'EduLearn',
                'client_type' => 'Education',
                'address' => '303 Knowledge Lane, Boston',
                'email' => 'contact@edulearn.com',
                'phone' => '678-901-2345',
            ],
            [
                'name' => 'Green Energy Solutions',
                'client_type' => 'Energy',
                'address' => '404 Solar Drive, Austin',
                'email' => 'info@greenenergy.com',
                'phone' => '789-012-3456',
            ],
            [
                'name' => 'HomeStyle Decor',
                'client_type' => 'Home & Living',
                'address' => '505 Design Street, Miami',
                'email' => 'support@homestyle.com',
                'phone' => '890-123-4567',
            ],
            [
                'name' => 'Tech Innovators',
                'client_type' => 'IT Services',
                'address' => '606 Innovation Road, Seattle',
                'email' => 'service@techinnovators.com',
                'phone' => '901-234-5678',
            ],
            [
                'name' => 'Travel Explorer',
                'client_type' => 'Travel & Tourism',
                'address' => '707 Adventure Blvd, San Francisco',
                'email' => 'info@travelexplorer.com',
                'phone' => '012-345-6789',
            ],
        ];

        foreach ($clients as $client) {
            Client::updateOrCreate($client);
        }
    }
}
