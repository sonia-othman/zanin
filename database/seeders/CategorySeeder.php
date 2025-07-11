<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            'Core Software & Development' => [
                'Python',
                'JavaScript',
                'Web Development',
                'Mobile Development',
            ],
            'Web & Internet' => [
                'React',
                'Vue.js',
                'APIs & Microservices',
            ],
            'Databases & Data' => [
                'MySQL',
                'MongoDB',
                'Big Data',
            ],
            'Networking & Infrastructure' => [
                'Network Protocols',
                'Cloud Computing',
                'DevOps',
            ],
            'Security & Privacy' => [
                'Cryptography',
                'Network Security',
                'Data Privacy',
            ],
            'AI, ML & Emerging Tech' => [
                'Machine Learning',
                'Artificial Intelligence',
                'Blockchain',
            ],
            'Internet of Things (IoT)' => [
                'Smart Devices',
                'Home Automation',
                'Wearables',
            ],
            'Scientific & Data Science' => [
                'Data Analysis',
                'Visualization',
                'Scientific Computing',
            ],
            'Software Engineering' => [
                'Design Patterns',
                'Testing',
                'Project Management',
            ],
            'Tools & Practices' => [
                'Git',
                'CI/CD',
                'Containers',
            ],
            'UI/UX & Design' => [
                'User Experience',
                'User Interface',
                'Graphic Design',
            ],
            'Systems & Admin' => [
                'Linux',
                'Windows Server',
                'Virtualization',
            ],
        ];

        foreach ($categories as $categoryName => $subCategories) {
            $category = Category::firstOrCreate([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
            ]);

            foreach ($subCategories as $subCategoryName) {
                SubCategory::firstOrCreate([
                    'name' => $subCategoryName,
                    'slug' => Str::slug($subCategoryName),
                    'category_id' => $category->id,
                ]);
            }
        }
    }
}
