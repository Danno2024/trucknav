<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use Illuminate\Database\Seeder;

class ForumCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'General Discussion', 'slug' => 'general-discussion', 'description' => 'Chat about anything trucking-related.', 'sort_order' => 1],
            ['name' => 'Route Reports & Hazards', 'slug' => 'route-reports-hazards', 'description' => 'Report road hazards, closures, and restriction updates.', 'sort_order' => 2],
            ['name' => 'Vehicle Tips & Tricks', 'slug' => 'vehicle-tips-tricks', 'description' => 'Maintenance tips, gear reviews, and vehicle advice.', 'sort_order' => 3],
            ['name' => 'Off-Topic', 'slug' => 'off-topic', 'description' => 'Non-trucking chat. Keep it friendly.', 'sort_order' => 4],
        ];

        foreach ($categories as $category) {
            ForumCategory::create($category);
        }
    }
}
