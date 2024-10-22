<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OurServices;

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
                'price' => 199.99,
                'duration' => 30,
                'duration_type' => 'days',
                'category' => 'Marketing',
                'status' => 'active',
            ],
            [
                'name' => 'Web Development',
                'description' => 'Building and maintaining websites; work includes web design, web publishing, web programming, and database management.',
                'price' => 1499.99,
                'duration' => 8,
                'duration_type' => 'weeks',
                'category' => 'Development',
                'status' => 'active',
            ],
            [
                'name' => 'Graphic Design',
                'description' => 'Creating visual content to communicate messages.',
                'price' => 499.99,
                'duration' => 4,
                'duration_type' => 'weeks',
                'category' => 'Design',
                'status' => 'active',
            ],
            [
                'name' => 'SEO Optimization',
                'description' => 'Improving the quality and quantity of website traffic to a website or a web page from search engines.',
                'price' => 299.99,
                'duration' => 6,
                'duration_type' => 'months',
                'category' => 'Marketing',
                'status' => 'active',
            ],
            [
                'name' => 'Content Writing',
                'description' => 'Planning, writing and editing web content, typically for digital marketing purposes.',
                'price' => 99.99,
                'duration' => 2,
                'duration_type' => 'weeks',
                'category' => 'Writing',
                'status' => 'active',
            ],
        ];

        foreach ($services as $service) {
            OurServices::updateOrCreate(['name' => $service['name']], $service);
        }
    }
}
