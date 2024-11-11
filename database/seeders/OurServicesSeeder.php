<?php

namespace Database\Seeders;

use App\Models\OurServices;
use Illuminate\Database\Seeder;

class OurServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name' => 'Digital Marketing',
                'description' => 'Promoting products or services using digital channels to reach consumers.',
                'price' => 30000,
                'duration' => 3,
                'duration_type' => 'months',
                'category' => 'Marketing',
                'status' => 'active',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Building and maintaining websites; work includes web design, web publishing, web programming, and database management.',
                'price' => 30000,
                'duration' => 12,
                'duration_type' => 'months',
                'category' => 'Development',
                'status' => 'active',
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Creating visual content to communicate messages.',
                'price' => 50000,
                'duration' => 6,
                'duration_type' => 'months',
                'category' => 'Design',
                'status' => 'active',
            ],
            [
                'name' => 'SEO Optimization',
                'description' => 'Improving the quality and quantity of website traffic to a website or a web page from search engines.',
                'price' => 30000,
                'duration' => 6,
                'duration_type' => 'months',
                'category' => 'Marketing',
                'status' => 'active',
            ],
            [
                'name' => 'Content Writing',
                'description' => 'Planning, writing and editing web content, typically for digital marketing purposes.',
                'price' => 25000,
                'duration' => 12,
                'duration_type' => 'months',
                'category' => 'Writing',
                'status' => 'active',
            ],
        ];

        foreach ($services as $service) {
            OurServices::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
