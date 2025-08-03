<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Convert content to jsonb using explicit cast
        DB::statement('ALTER TABLE article_translations ALTER COLUMN content TYPE jsonb USING content::jsonb');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to text if needed
        DB::statement('ALTER TABLE article_translations ALTER COLUMN content TYPE text');
    }
};
