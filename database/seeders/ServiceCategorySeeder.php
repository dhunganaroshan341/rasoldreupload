<?php

namespace Database\Seeders;
use App\Models\ServiceCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Define initial categories
        $categories = [
            ['name' => 'Web Developement'],
            ['name' => 'Marketing'],
            ['name' => 'Graphics'],
            // Add more categories as needed
        ];

        // Insert categories into database
        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate($category);
        }
    }
}
