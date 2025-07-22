<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import DB facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Truncate the 6 tables before seeding
        DB::table('sub_category_translations')->truncate();
        DB::table('sub_categories')->truncate();
        DB::table('categories')->truncate();
        DB::table('category_translations')->truncate();
        DB::table('article_translations')->truncate();
        DB::table('articles')->truncate();
    //$this->call(CategorySeeder::class);

    }
}
